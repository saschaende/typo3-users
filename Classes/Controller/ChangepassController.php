<?php

namespace SaschaEnde\Users\Controller;

use SaschaEnde\Users\Domain\Model\Passwordchange;
use SaschaEnde\Users\Domain\Model\User;
use SaschaEnde\Users\Domain\Repository\UserRepository;
use t3h\t3h;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ChangepassController extends ActionController {

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
     * @param \SaschaEnde\Users\Domain\Model\Passwordchange $passwordchange
     * @param array $errors
     */
    public function formAction($passwordchange = null, $errors = []) {

        // Abort if not logged
        if(!$this->user){return;}

        if ($passwordchange == null) {
            $passwordchange = new Passwordchange();
        }

        $this->view->assignMultiple([
            'errors' => $errors,
            'passwordchange' => $passwordchange,
            'user' => $this->user
        ]);
    }

    /**
     * @param \SaschaEnde\Users\Domain\Model\Passwordchange $passwordchange
     */
    public function submitAction(Passwordchange $passwordchange = null) {

        // Abort if not logged
        if(!$this->user){return;}

        $errors = [];

        // Minumum length of password
        if (mb_strlen($passwordchange->getPassword()) < 6) {
            $errors['password'][] = '7';
        } // Same passwords
        elseif ($passwordchange->getPassword() != $passwordchange->getRepeat()) {
            $errors['password'][] = '8';
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
                    'passwordchange' => $passwordchange
                ]
            );
        } else {

            // ---------------------------------------------------------------------
            // SUCCESS, set new password
            // ---------------------------------------------------------------------

            $this->user->setPassword(t3h::Password()->getHashedPassword($passwordchange->getPassword()));
            $this->frontendUserRepository->update($this->user);


        }

    }

}