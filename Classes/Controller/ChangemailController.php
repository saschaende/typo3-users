<?php

namespace SaschaEnde\Users\Controller;

use SaschaEnde\Users\Domain\Model\Mailchange;
use SaschaEnde\Users\Domain\Model\User;
use SaschaEnde\Users\Domain\Repository\BannedHostsRepository;
use SaschaEnde\Users\Domain\Repository\UserRepository;
use t3h\t3h;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class ChangemailController extends ActionController {

    /**
     * @var User
     */
    protected $user;

    /**
     * @var UserRepository
     */
    protected $frontendUserRepository;

    /**
     * @var BannedHostsRepository
     */
    protected $bannedHostsRepository;


    public function initializeAction() {

        $this->frontendUserRepository = $this->objectManager->get(UserRepository::class);

        /** @var Typo3QuerySettings $querysettings */
        $querysettings = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings::class);
        $querysettings->setStoragePageIds([
            $this->settings['usersFolder']
        ]);
        // We also have to check not yet enabled accounts
        $querysettings->setIgnoreEnableFields(true);
        $this->frontendUserRepository->setDefaultQuerySettings($querysettings);

        // Load user object
        $this->user = $this->frontendUserRepository->findByUid(t3h::FrontendUser()->getCurrentUser()->user['uid']);

        // Banned Hosts Repo
        $this->bannedHostsRepository = $this->objectManager->get(BannedHostsRepository::class);
        $this->bannedHostsRepository->setDefaultQuerySettings(t3h::Database()->getQuerySettings());
    }

    /**
     * @param \SaschaEnde\Users\Domain\Model\Mailchange $mailchange
     * @param array $errors
     */
    public function formAction($mailchange = null, $errors = []){

        // Abort if not logged
        if(!$this->user){return;}

        if($mailchange == null){
            $mailchange = new Mailchange();
        }

        $this->view->assignMultiple([
            'errors' => $errors,
            'mailchange' => $mailchange,
            'user' => $this->user
        ]);
    }

    /**
     * @param \SaschaEnde\Users\Domain\Model\Mailchange $mailchange
     */
    public function submitAction(Mailchange $mailchange){

        // Abort if not logged
        if(!$this->user){return;}

        $errors = [];

        // No email
        if (empty($mailchange->getEmail())) {
            $errors['email'][] = '4';
        } // E-Mail Format
        elseif (!filter_var($mailchange->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $errors['email'][] = '5';
        } // Check if banned
        elseif ($this->bannedHostsRepository->checkIfBanned($mailchange->getEmail())) {
            $errors['email'][] = '10';
        }

        if (count($errors) >= 1) {

            // ---------------------------------------------------------------------
            // ERRORS ... So show the form again
            // ---------------------------------------------------------------------
            $this->forward(
                'form',
                null,
                null,
                [
                    'errors' => $errors,
                    'mailchange'    => $mailchange
                ]
            );
        } else {

            if (!$this->frontendUserRepository->findOneByEmail($mailchange->getEmail())) {
                // Send email with confirmation link and save

                // Save mail
                $this->user->setUsersNewemail($mailchange->getEmail());

                // Hash
                $emailHash = md5(uniqid() . time());
                $this->user->setUsersNewemailhash($emailHash);

                // Save to database
                $this->frontendUserRepository->update($this->user);


                // Make link, as short as possible
                $link = t3h::Uri()->getByPid(
                    $this->settings['confirmmailchangePage'],
                    false,
                    true,
                    [
                        'uid'   => $this->user->getUid(),
                        'changeemailHash'  => $emailHash
                    ]
                );


                // Now lets send the email
                t3h::Mail()->sendDynamicTemplate(
                    $mailchange->getEmail(),
                    $this->settings['senderEmail'],
                    $this->settings['senderName'],
                    $this->settings['subject'],
                    'tx_users',
                    'Email',
                    ['user' => $this->user, 'link' => $link],
                    [],
                    1,
                    $this->controllerContext
                );

            }else{

                // Do nothing here, just show the message that we sent an email (for security and enumeration reasons)

            }

        }
    }


    /**
     * Confirmation for mailchange
     */
    public function confirmAction() {

        $arguments = $_GET;

        $this->frontendUserRepository->setDefaultQuerySettings(t3h::Database()->getQuerySettings());

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

}