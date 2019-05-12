<?php

namespace SaschaEnde\Users\Controller;

use SaschaEnde\Users\Domain\Model\Mailchange;
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

    }

}