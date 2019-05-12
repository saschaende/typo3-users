<?php

namespace SaschaEnde\Users\Controller;

use SaschaEnde\Users\Domain\Model\Mailchange;
use SaschaEnde\Users\Domain\Model\User;
use SaschaEnde\Users\Domain\Repository\BannedHostsRepository;
use SaschaEnde\Users\Domain\Repository\UserRepository;
use t3h\t3h;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
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
        $this->user = $this->frontendUserRepository->findByUid(t3h::FrontendUser()->getCurrentUser()->user['uid']);

        // Banned Hosts Repo
        $this->bannedHostsRepository = $this->objectManager->get(BannedHostsRepository::class);
        $this->bannedHostsRepository->setDefaultQuerySettings(t3h::Database()->getQuerySettings());

        /** @var Typo3QuerySettings $querysettings */
        $querysettings = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings::class);
        $querysettings->setStoragePageIds([
            $this->settings['usersFolder']
        ]);
        // Also check for disabled accounts - this is also a verification, if the user reacts to this link via email
        $querysettings->setIgnoreEnableFields(true);
        $this->frontendUserRepository->setDefaultQuerySettings($querysettings);
    }

    /**
     * @param \SaschaEnde\Users\Domain\Model\Mailchange $mailchange
     * @param array $errors
     */
    public function formAction($mailchange = null, $errors = []){

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

            }else{
                DebuggerUtility::var_dump('EXISTS');
            }

        }
    }

}