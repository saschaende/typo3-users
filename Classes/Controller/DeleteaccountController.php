<?php

namespace SaschaEnde\Users\Controller;

use SaschaEnde\Users\Domain\Model\User;
use SaschaEnde\Users\Domain\Repository\UserRepository;
use t3h\t3h;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

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

    }


    public function submitAction() {
        // Send email with delete link
        // @todo complete this
    }

    public function confirmAction(){
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