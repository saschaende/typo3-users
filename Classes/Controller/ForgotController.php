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


    /**
     * Redirect to change form, if uid found. Otherwise show form with username/email input.
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function formAction(){

        if(isset($_GET['uid'])){
            $this->forward('changeform',null,null,$_GET);
        }
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

            // Make link, as short as possible
            $link = t3h::Uri()->getByPid(
                t3h::Page()->getPid(),
                false,
                true,
                [
                    'uid'=>$user->getUid(),
                    'forgotHash'=>$forgotHash
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
                ['user'=>$user,'link'=>$link],
                [],
                1,
                $this->controllerContext
            );
        }
    }

    public function changeformAction(){
        $arguments = $this->request->getArguments();
        DebuggerUtility::var_dump($arguments);
    }

    public function changeformsubmitAction(){

    }
}