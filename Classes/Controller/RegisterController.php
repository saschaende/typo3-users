<?php

namespace SaschaEnde\Users\Controller;

use SaschaEnde\Users\Domain\Model\Registration;
use SaschaEnde\Users\Domain\Model\User;
use SaschaEnde\Users\Domain\Repository\BannedHostsRepository;
use SaschaEnde\Users\Domain\Repository\BannedMailsRepository;
use SaschaEnde\Users\Domain\Repository\UserRepository;
use SJBR\StaticInfoTables\Domain\Repository\CountryRepository;
use t3h\t3h;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserGroupRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Class RegisterController
 * @package SaschaEnde\Users\Controller
 * @todo Show page, if the user is already logged in (or configure redirect page?)
 */
class RegisterController extends ActionController
{

    /**
     * @var CountryRepository
     */
    protected $countryRepository;

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

    /**
     * @var BannedMailsRepository
     */
    protected $bannedmailsRepository;

    /**
     * @var FrontendUserGroup
     */
    protected $test;

    public function initializeAction()
    {

        // Load groups repo
        $this->frontendUserGroupRepository = $this->objectManager->get(FrontendUserGroupRepository::class);
        $this->frontendUserGroupRepository->setDefaultQuerySettings(t3h::Database()->getQuerySettings());

        // Banned Hosts Repo
        $this->bannedHostsRepository = $this->objectManager->get(BannedHostsRepository::class);
        $this->bannedHostsRepository->setDefaultQuerySettings(t3h::Database()->getQuerySettings());

        // Banned Mails Repo
        $this->bannedmailsRepository = $this->objectManager->get(BannedMailsRepository::class);
        $this->bannedmailsRepository->setDefaultQuerySettings(t3h::Database()->getQuerySettings());

        // Load user repo
        $this->frontendUserRepository = $this->objectManager->get(UserRepository::class);

        $this->countryRepository = $this->objectManager->get(CountryRepository::class);
        $this->countryRepository->setDefaultOrderings(['shortNameEn' => QueryInterface::ORDER_ASCENDING]);

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
    public function formAction($registration = null, $errors = [])
    {

        // Redirect user to confirmation page
        if (isset($_GET['uid']) && isset($_GET['registerHash'])) {
            $this->forward('confirm', null, null, $_GET);
        }
        // redirect admin to approval page
        if (isset($_GET['uid']) && isset($_GET['approvalHash'])) {
            $this->forward('approval', null, null, $_GET);
        }
        // redirect user to clearance page after he got approved by admin
        if (isset($_GET['uid']) && isset($_GET['clearance'])) {
            $this->forward('clearance', null, null, $_GET);
        }

        if ($registration == null) {
            $registration = new Registration();
        }

        // Setup optionalfields
        $optionalFields = [];
        $requiredFields = explode(',', $this->settings['requiredFields']);
        $requiredFields = array_filter($requiredFields); // Remove empty fields
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
            'optionalFields' => $optionalFields,
            'countries' => $this->countryRepository->findAll()
        ]);
    }

    /**
     * @param \SaschaEnde\Users\Domain\Model\Registration $registration
     */
    public function submitAction(Registration $registration)
    {

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
        } elseif ($this->bannedmailsRepository->findOneByEmail($registration->getEmail())) {
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
        $requiredFields = array_filter($requiredFields); // Remove empty fields
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
                    3,
                    $this->controllerContext
                );

                // Only show the data the user entered
                $this->view->assignMultiple([
                    'user' => $registration
                ]);
            } else {
                // User does not exist, so add user now
                $user = $this->objectManager->get(User::class);
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

                // User needs additional approval by admin
                if ($this->settings['registrationApproval']) {
                    $approvalHash = md5(uniqid() . time());
                    $user->setUsersApprovalhash($approvalHash);
                } else {
                    $user->setUsersApprovalhash('');
                }

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
                    [
                        'user' => $user,
                        'registrationApproval' => $this->settings['registrationApproval'],
                        'link' => $link
                    ],
                    [],
                    3,
                    $this->controllerContext
                );

                /** @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher */
                $signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
                $signalSlotDispatcher->dispatch(__CLASS__, 'afterRegistrationSuccess', [$user, $this]);

                $this->view->assignMultiple([
                    'user' => $user
                ]);
            }

        }
    }

    /**
     * User clicked on confirm link in email to complete the registration
     *
     * @throws \ReflectionException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function confirmAction()
    {
        $arguments = $this->request->getArguments();

        // Load userdata
        /** @var User $user */
        $user = $this->frontendUserRepository->findOneByUid($arguments['uid']);


        if ($user && !empty($user->getUsersRegisterhash()) && $user->getUsersRegisterhash() == $arguments['registerHash']) {
            // user activated himself

            $user->setUsersRegisterhash('');

            $approvalHash = $user->getUsersApprovalhash();
            $approvalNeeded = (bool)$approvalHash;
            if ($approvalHash) {
                // approval hash is set means approval is needed

                // send Email to admin for approval

                // Make link, as short as possible
                $linkApprove = t3h::Uri()->getByPid(
                    t3h::Page()->getPid(),
                    false,
                    true,
                    [
                        'uid' => $user->getUid(),
                        'approvalHash' => $approvalHash,
                        'decision' => 'approve'
                    ]
                );

                $linkReject = t3h::Uri()->getByPid(
                    t3h::Page()->getPid(),
                    false,
                    true,
                    [
                        'uid' => $user->getUid(),
                        'approvalHash' => $approvalHash,
                        'decision' => 'reject'
                    ]
                );

                // Send email
                t3h::Mail()->sendDynamicTemplate(
                    $this->settings['recipientEmailApproval'],
                    $this->settings['senderEmail'],
                    $this->settings['senderName'],
                    ($this->settings['subjectApproval'] ?: $this->settings['subject']),
                    'tx_users',
                    'EmailApproval',
                    ['user' => $user, 'linkApprove' => $linkApprove, 'linkReject' => $linkReject],
                    [],
                    3,
                    $this->controllerContext
                );

            } else {
                $user->setDisable(false);
            }
            $this->frontendUserRepository->update($user);
            t3h::Database()->persistAll();

            // Automatically login?
            if ($this->settings['login'] && !$approvalNeeded) {
                t3h::FrontendUser()->loginUser($user->getUsername());
            }

            $this->view->assignMultiple([
                'user' => $user,
                'success' => true,
                'approvalNeeded' => $approvalNeeded
            ]);
        } else {
            $this->view->assignMultiple([
                'success' => false
            ]);
        }
    }


    /**
     * Admin clicked on approval link in email to approve or reject user registration
     *
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function approvalAction()
    {
        $arguments = $this->request->getArguments();

        /** @var User $user */
        $user = $this->frontendUserRepository->findOneByUid($arguments['uid']);

        if ($user && !empty($user->getUsersApprovalhash()) && $user->getUsersApprovalhash() == $arguments['approvalHash'] && isset($arguments['decision'])) {
            $user->setUsersApprovalhash('');

            $approved = ($arguments['decision'] === 'approve');

            if ($approved) {
                $user->setDisable(false);

                // send clearance email

                $loginPid = $this->settings['loginPid'] ?: t3h::Page()->getPid();

                // Make link, as short as possible
                $link = t3h::Uri()->getByPid(
                    $loginPid,
                    false,
                    true,
                    [
                        'uid' => $user->getUid(),
                        'clearance' => 1
                    ]
                );

                // Send email
                t3h::Mail()->sendDynamicTemplate(
                    $user->getEmail(),
                    $this->settings['senderEmail'],
                    $this->settings['senderName'],
                    $this->settings['subjectAccepted'],
                    'tx_users',
                    'EmailAccepted',
                    ['user' => $user, 'link' => $link],
                    [],
                    3,
                    $this->controllerContext
                );

            } else {
                $user->setDisable(true);

                // Send reject email
                t3h::Mail()->sendDynamicTemplate(
                    $user->getEmail(),
                    $this->settings['senderEmail'],
                    $this->settings['senderName'],
                    $this->settings['subjectRejected'],
                    'tx_users',
                    'EmailRejected',
                    ['user' => $user],
                    [],
                    3,
                    $this->controllerContext
                );
            }

            $this->frontendUserRepository->update($user);
            t3h::Database()->persistAll();

            $this->view->assignMultiple([
                'user' => $user,
                'success' => true,
                'approved' => $approved
            ]);
        } else {
            $this->view->assignMultiple([
                'user' => $user,
                'success' => false
            ]);
        }
    }


    /**
     * User was approved and got an email. The link in the email call this action which just show a message.
     * This is only used if approval was activated and no loginPid was configured in registration plugin.
     */
    public function clearanceAction()
    {
        $arguments = $this->request->getArguments();

        // Load userdata
        /** @var User $user */
        $user = $this->frontendUserRepository->findOneByUid($arguments['uid']);

        if ($user && !$user->isDisable()) {
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
    public function redirectAction()
    {
        $link = t3h::Uri()->getByPid($this->settings['successLink']);
        $this->redirectToUri($link);
    }

}
