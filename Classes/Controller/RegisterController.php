<?php

namespace SaschaEnde\Users\Controller;

use SaschaEnde\Users\Domain\Model\Registration;
use SaschaEnde\Users\Domain\Model\User;
use SaschaEnde\Users\Domain\Repository\BannedHostsRepository;
use SaschaEnde\Users\Domain\Repository\UserRepository;
use t3h\t3h;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserGroupRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

/**
 * Class RegisterController
 * @package SaschaEnde\Users\Controller
 * @todo Show page, if the user is already logged in (or configure redirect page?)
 */
class RegisterController extends ActionController {

    /**
     * @var UserRepository
     */
    protected $frontendUserRepository;

    /**
     * @var FrontendUserGroupRepository
     */
    protected $frontendUserGroupRepository;

    /**
     * @var BannedHostsRepository
     */
    protected $bannedHostsRepository;

    public function initializeAction() {

        // Load groups repo
        $this->frontendUserGroupRepository = $this->objectManager->get(FrontendUserGroupRepository::class);
        $this->frontendUserGroupRepository->setDefaultQuerySettings(t3h::Database()->getQuerySettings());

        // Banned Hosts Repo
        $this->bannedHostsRepository = $this->objectManager->get(BannedHostsRepository::class);
        $this->bannedHostsRepository->setDefaultQuerySettings(t3h::Database()->getQuerySettings());

        // Load user repo
        $this->frontendUserRepository = $this->objectManager->get(UserRepository::class);

        /** @var Typo3QuerySettings $querysettings */
        $querysettings = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings::class);
        $querysettings->setStoragePageIds([
            $this->settings['usersFolder']
        ]);
        // Für die Überprüfung müssen wir auch inaktive Accounts prüfen
        $querysettings->setIgnoreEnableFields(true);
        $this->frontendUserRepository->setDefaultQuerySettings($querysettings);
    }

    /**
     * @param \SaschaEnde\Users\Domain\Model\Registration $registration
     * @param array $errors
     */
    public function formAction($registration = null, $errors = []) {

        // Redirect to confirmation page
        if (isset($_GET['uid']) && isset($_GET['registerHash'])) {
            $this->forward('confirm', null, null, $_GET);
        }

        if (isset($_GET['uid']) && isset($_GET['changeemailHash'])) {
            $this->forward('confirmmailchange', null, null, $_GET);
        }

        if (isset($_GET['uid']) && isset($_GET['deleteHash'])) {
            $this->forward('confirmdeleteaccount', null, null, $_GET);
        }


        if ($registration == null) {
            $registration = new Registration();
        }

        // Setup optionalfields
        $optionalFields = [];
        $requiredFields = explode(',', $this->settings['requiredFields']);
        foreach (explode(',', $this->settings['optionalFields']) as $field) {
            if (in_array($field, $requiredFields)) {
                $required = true;
            } else {
                $required = false;
            }
            $optionalFields[] = [
                'id' => $field,
                'required' => $required
            ];
        }

        $this->view->assignMultiple([
            'errors' => $errors,
            'registration' => $registration,
            'optionalFields' => $optionalFields
        ]);
    }

    /**
     * @param \SaschaEnde\Users\Domain\Model\Registration $registration
     */
    public function submitAction(Registration $registration) {

        // Lets make some checks
        $errors = [];

        // Username min 3 and max 20
        if (mb_strlen($registration->getUsername()) < 3 || mb_strlen($registration->getUsername()) > 20) {
            $errors['username'][] = '1';
        }

        // Sonderzeichen im Username
        if (preg_match('/[^a-zA-Z0-9]/', $registration->getUsername()) == 1) {
            $errors['username'][] = '2';
        }

        // Check if username exists
        if ($this->frontendUserRepository->findOneByUsername($registration->getUsername())) {
            $errors['username'][] = '3';
        }

        // No email
        if (empty($registration->getEmail())) {
            $errors['email'][] = '4';
        } // E-Mail Format
        elseif (!filter_var($registration->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $errors['email'][] = '5';
        } // Check if banned
        elseif ($this->bannedHostsRepository->checkIfBanned($registration->getEmail())) {
            $errors['email'][] = '10';
        }

        // Minumum length of password
        if (mb_strlen($registration->getPassword()) < 6) {
            $errors['password'][] = '7';
        } // Same passwords
        elseif ($registration->getPassword() != $registration->getPasswordrepeat()) {
            $errors['password'][] = '8';
        }

        // ---------------------------------------------------------------------
        // Check required fields

        $requiredFields = explode(',', $this->settings['requiredFields']);
        foreach ($requiredFields as $fieldname) {
            $func = 'get' . GeneralUtility::underscoredToUpperCamelCase($fieldname);
            if (empty($registration->$func())) {
                $errors[$fieldname][] = '9';
            }
        }

        // ---------------------------------------------------------------------

        if (count($errors) >= 1) {


            // ---------------------------------------------------------------------
            // ERRORS ... So show the form again
            // ---------------------------------------------------------------------
            $this->forward(
                'form',
                null,
                null,
                [
                    'registration' => $registration,
                    'errors' => $errors
                ]
            );
        } else {

            // ---------------------------------------------------------------------
            // SUCCESS
            // ---------------------------------------------------------------------

            if ($user = $this->frontendUserRepository->findOneByEmail($registration->getEmail())) {
                // This user exists, send an email with some information
                // Send email
                /** @var User $user */
                $link = t3h::Uri()->getByPid($this->settings['forgotpassPid']);

                t3h::Mail()->sendDynamicTemplate(
                    $user->getEmail(),
                    $this->settings['senderEmail'],
                    $this->settings['senderName'],
                    $this->settings['subject'],
                    'tx_users',
                    'EmailExists',
                    ['user' => $user, 'link' => $link],
                    [],
                    1,
                    $this->controllerContext
                );

                // Only show the data the user entered
                $this->view->assignMultiple([
                    'user' => $registration
                ]);
            } else {
                // User does not exist, so add user now
                $user = new User();
                $user->setUsername($registration->getUsername());
                $user->setEmail($registration->getEmail());
                $user->setPassword(t3h::Password()->getHashedPassword($registration->getPassword()));
                $user->setDisable(true);
                $user->setPid($this->settings['usersFolder']);

                // User groups
                foreach (explode(',', $this->settings['userGroups']) as $groupId) {
                    $user->addUsergroup($this->frontendUserGroupRepository->findOneByUid($groupId));
                }

                // Add optional fields
                $requiredFields = explode(',', $this->settings['optionalFields']);
                foreach ($requiredFields as $fieldname) {
                    $getFunc = 'get' . GeneralUtility::underscoredToUpperCamelCase($fieldname);
                    $setFunc = 'set' . GeneralUtility::underscoredToUpperCamelCase($fieldname);
                    $user->$setFunc($registration->$getFunc());
                }

                // Hash
                $registerHash = md5(uniqid() . time());
                $user->setUsersRegisterhash($registerHash);

                // ID of website
                $user->setUsersWebsite(t3h::Website()->getWebsiteRootPid());

                // Set language
                $user->setUsersLanguage(t3h::FrontendUser()->getLanguage());

                // Add now
                $this->frontendUserRepository->add($user);

                // Save directly
                t3h::Database()->persistAll();

                // Make link, as short as possible
                $link = t3h::Uri()->getByPid(
                    t3h::Page()->getPid(),
                    false,
                    true,
                    [
                        'uid' => $user->getUid(),
                        'registerHash' => $registerHash
                    ]
                );


                // Send email
                t3h::Mail()->sendDynamicTemplate(
                    $user->getEmail(),
                    $this->settings['senderEmail'],
                    $this->settings['senderName'],
                    $this->settings['subject'],
                    'tx_users',
                    'Email',
                    ['user' => $user, 'link' => $link],
                    [],
                    1,
                    $this->controllerContext
                );

                $this->view->assignMultiple([
                    'user' => $user
                ]);
            }

        }


    }

    public function confirmAction() {
        $arguments = $this->request->getArguments();

        // Load userdata
        /** @var User $user */
        $user = $this->frontendUserRepository->findOneByUid($arguments['uid']);


        if ($user && !empty($user->getUsersRegisterhash()) && $user->getUsersRegisterhash() == $arguments['registerHash']) {
            $user->setUsersRegisterhash('');
            $user->setDisable(false);
            $this->frontendUserRepository->update($user);
            t3h::Database()->persistAll();

            // Automatically login?
            if ($this->settings['login']) {
                t3h::FrontendUser()->loginUser($user->getUsername());
            }

            $this->view->assignMultiple([
                'user' => $user,
                'success' => true
            ]);
        } else {
            $this->view->assignMultiple([
                'success' => false
            ]);
        }
    }

    /**
     * Seperate redirect ation, so given links will also work with user areas...
     */
    public function redirectAction() {
        $link = t3h::Uri()->getByPid($this->settings['successLink']);
        $this->redirectToUri($link);
    }

    /**
     * Confirmation for mailchange
     */
    public function confirmdeleteaccountAction() {

        $arguments = $this->request->getArguments();

        // Load userdata
        /** @var User $user */
        $user = $this->frontendUserRepository->findOneByUid($arguments['uid']);

        $verified = $this->verifyDeleteAccount($user, $arguments);

        $deleted = false;

        if ($verified) {
            $this->frontendUserRepository->remove($user);
            $deleted = true;
        }

        $this->view->assignMultiple([
            'user' => $user,
            'deleted' => $deleted
        ]);

    }

    /**
     * Confirmation for mailchange
     */
    public function confirmmailchangeAction() {

        $arguments = $this->request->getArguments();

        // Load userdata
        /** @var User $user */
        $user = $this->frontendUserRepository->findOneByUid($arguments['uid']);

        $verified = $this->verifyMailChange($user, $arguments);

        if ($verified) {
            $user->setEmail($user->getUsersNewemail());
            $user->setUsersNewemail('');
            $user->setUsersNewemailhash('');
            $this->frontendUserRepository->update($user);
        }

        $this->view->assignMultiple([
            'user' => $user,
            'verified' => $verified
        ]);

    }

    /**
     * Check the data
     * @param User $user
     * @param $arguments
     * @return bool
     */
    private function verifyMailChange(User $user, $arguments) {
        // stop if there is no user
        if (!$user) {
            return false;
        }

        // empty forgothash
        if (empty($arguments['changeemailHash'])) {
            return false;
        }

        // stop if it is not the hash found in the database
        if ($user->getUsersNewemailhash() != $arguments['changeemailHash']) {
            return false;
        }

        // check, if meanwhile there is another user account with that email address
        /** @var QueryResult $users */
        $users = $this->frontendUserRepository->findByEmail($user->getUsersNewemail());
        if ($users->count() >= 1) {
            return false;
        }

        return true;
    }


    /**
     * Check the data
     * @param User $user
     * @param $arguments
     * @return bool
     * @todo Change function names (add tca, model, sql....)
     */
    private function verifyDeleteAccount(User $user, $arguments) {
        // stop if there is no user
        if (!$user) {
            return false;
        }

        // empty forgothash
        if (empty($arguments['deleteHash'])) {
            return false;
        }

        // stop if it is not the hash found in the database
        if ($user->getUsersNewemailhash() != $arguments['deleteHash']) {
            return false;
        }

        // stop, if timestamp is older then now
        if ($user->getUsersForgothashValid()->getTimestamp() < time()) {
            return false;
        }

        return true;
    }

}