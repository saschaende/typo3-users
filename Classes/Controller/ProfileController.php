<?php

namespace SaschaEnde\Users\Controller;

use SaschaEnde\Users\Domain\Repository\UserRepository;
use t3h\t3h;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ProfileController extends ActionController
{

    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function initializeAction()
    {
        $this->userRepository = t3h::injectClass(UserRepository::class);
    }

    public function showAction()
    {
        $arg = $this->request->getArguments();
        $user = $this->userRepository->findByUid($arg['user']);

        $this->view->assignMultiple([
            'user' => $user
        ]);
    }

}