<?php

namespace SaschaEnde\Users\Controller;

use SaschaEnde\Users\Domain\Model\User;
use SaschaEnde\Users\Domain\Repository\UserRepository;
use t3h\t3h;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class DeleteaccountController extends ActionController {

    /**
     * @var User
     */
    protected $user;

    /**
     * @var UserRepository
     */
    protected $frontendUserRepository;


    public function initializeAction() {

        $this->frontendUserRepository = $this->objectManager->get(UserRepository::class);

        // Ignore store page, because thats not relevant here
        $this->frontendUserRepository->setDefaultQuerySettings(t3h::Database()->getQuerySettings());

        // load user object
        $this->user = $this->frontendUserRepository->findByUid(t3h::FrontendUser()->getCurrentUser()->user['uid']);

    }


    /**
     * @param array $errors
     */
    public function formAction() {
        // Nothing here, just template
    }


    public function submitAction() {

        // Make string and time
        $dt = new \DateTime();
        $dt->modify('+1 day');
        $this->user->setUsersDeletehashValid($dt);

        // Hash
        $emailHash = md5(uniqid() . time());
        $this->user->setUsersDeletehash($emailHash);

        // Save to database
        $this->frontendUserRepository->update($this->user);

        // Make link, as short as possible
        $link = t3h::Uri()->getByPid(
            $this->settings['deleteaccountconfirmPage'],
            false,
            true,
            [
                'uid'   => $this->user->getUid(),
                'deleteHash'  => $emailHash
            ]
        );


        // Now lets send the email
        t3h::Mail()->sendDynamicTemplate(
            $this->user->getEmail(),
            $this->settings['senderEmail'],
            $this->settings['senderName'],
            $this->settings['subject'],
            'tx_users',
            'Email',
            ['user' => $this->user, 'link' => $link],
            [],
            3,
            $this->controllerContext
        );
    }

    public function confirmAction(){

        $arguments = $_GET;

        $verified = false;

        // Load userdata
        /** @var User $user */
        $user = $this->frontendUserRepository->findOneByUid($arguments['uid']);

        if($user){
            $verified = $this->verifyDeleteAccount($user, $arguments);
        }


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
        if ($user->getUsersDeletehash() != $arguments['deleteHash']) {
            return false;
        }

        // stop, if timestamp is older then now
        if ($user->getUsersDeletehashValid()->getTimestamp() < time()) {
            return false;
        }

        return true;
    }

}