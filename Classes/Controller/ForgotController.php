<?php

namespace SaschaEnde\Users\Controller;

use SaschaEnde\Users\Domain\Model\User;
use SaschaEnde\Users\Domain\Repository\UserRepository;
use t3h\t3h;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

class ForgotController extends ActionController {

    /**
     * @var UserRepository
     */
    protected $frontendUserRepository;

    public function initializeAction() {
        $this->frontendUserRepository = $this->objectManager->get(UserRepository::class);

        /** @var Typo3QuerySettings $querysettings */
        $querysettings = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings::class);
        $querysettings->setStoragePageIds([
            $this->settings['usersFolder']
        ]);
        $this->frontendUserRepository->setDefaultQuerySettings($querysettings);
    }


    /**
     * Redirect to change form, if uid found. Otherwise show form with username/email input.
     */
    public function formAction() {

        if (isset($_GET['uid'])) {
            $this->forward('changeform', null, null, $_GET);
        }
    }

    /**
     * Submit username or email. Email to user will be sent, if user found.
     */
    public function formsubmitAction() {
        $arguments = $this->request->getArguments();

        // Load userdata
        /** @var User $user */
        $user = $this->frontendUserRepository->findOneByUsername($arguments['username']);

        // Kein User gefunden, dann versuche es mit der E-Mail Adresse
        if (!$user) {
            $user = $this->frontendUserRepository->findOneByEmail($arguments['username']);
        }

        // Send email, if user found
        if ($user) {
            /** @var User $user */
            // Make string and time
            $forgotHash = md5(uniqid() . time());
            $dt = new \DateTime();
            $dt->modify('+60 minutes');

            // Save into user account
            $user->setUsersForgothash($forgotHash);
            $user->setUsersForgothashValid($dt);
            $this->frontendUserRepository->update($user);

            // Make link, as short as possible
            $link = t3h::Uri()->getByPid(
                t3h::Page()->getPid(),
                false,
                true,
                [
                    'uid' => $user->getUid(),
                    'forgotHash' => $forgotHash
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
        }
    }

    /**
     * Form for setting a new password
     */
    public function changeformAction() {
        $arguments = $this->request->getArguments();

        // Load userdata
        /** @var User $user */
        $user = $this->frontendUserRepository->findOneByUid($arguments['uid']);

        // Überprüfe Hash
        if ($user) {
            $this->view->assignMultiple([
                'error' => $arguments['error'],
                'allowed' => $this->verifyPasswordChange($user, $arguments),
                'forgotHash' => $arguments['forgotHash'],
                'uid' => $arguments['uid']
            ]);
        } else {
            $this->view->assign('allowed', false);
        }
    }

    /**
     * Change the password now
     */
    public function changeformsubmitAction() {
        $arguments = $this->request->getArguments();

        // Load userdata
        /** @var User $user */
        $user = $this->frontendUserRepository->findOneByUid($arguments['uid']);

        if ($user && $this->verifyPasswordChange($user, $arguments) && mb_strlen($arguments['password']) >= 6 && $arguments['password'] == $arguments['passwordrepeat']) {
            // Change password
            $user->setPassword(t3h::Password()->getHashedPassword($arguments['password']));
            $user->setUsersForgothash('');
            $user->setUsersForgothashValid(0);
            $this->frontendUserRepository->update($user);

            // Automatically login?
            if ($this->settings['login']) {
                t3h::FrontendUser()->loginUser($user->getUsername());
            }
        } else {
            $link = t3h::Uri()->getByPid(
                t3h::Page()->getPid(),
                false,
                true,
                [
                    'error' => 1,
                    'uid' => $arguments['uid'],
                    'forgotHash' => $arguments['forgotHash']
                ]
            );
            $this->redirectToUri($link);
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
     * Check the data
     * @param User $user
     * @param $arguments
     * @return bool
     */
    private function verifyPasswordChange(User $user, $arguments) {
        if (!$user) {
            return false;
        }

        if ($user->getUsersForgothash() != $arguments['forgotHash']) {
            return false;
        }

        if ($user->getUsersForgothashValid()->getTimestamp() < time()) {
            return false;
        }

        return true;
    }
}