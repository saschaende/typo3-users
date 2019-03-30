<?php

namespace SaschaEnde\Users\Controller;

use SaschaEnde\Users\Domain\Model\User;
use SaschaEnde\Users\Domain\Repository\UserRepository;
use t3h\t3h;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

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


    public function formAction(){

    }

    public function formsubmitAction(){
        $arguments = $this->request->getArguments();

        // Load userdata
        /** @var User $user */
        $user = $this->frontendUserRepository->findOneByUsername($arguments['username']);

        // Kein User gefunden, dann versuche es mit der E-Mail Adresse
        if (!$user) {
            $user = $this->frontendUserRepository->findOneByEmail($arguments['username']);
        }

        // Send email, if user found
        if($user){
            /** @var User $user */
            // Make string and time
            $forgotHash = md5(uniqid().time());
            $dt = new \DateTime();
            $dt->modify('+60 minutes');

            // Save into user account
            $user->setUsersForgothash($forgotHash);
            $user->setUsersForgothashValid($dt);
            $this->frontendUserRepository->update($user);

            // Send email
            t3h::Mail()->sendTemplate(
                $user->getEmail(),
                $this->settings['senderEmail'],
                $this->settings['senderName'],
                $this->settings['subject'],
                'users',
                'Resources/Private/Templates/Forgot/Email.html',
                ['user'=>$user],
                [],
                1,
                $this->controllerContext
            );
        }
    }

    public function changeformAction(){

    }

    public function changeformsubmitAction(){

    }
}