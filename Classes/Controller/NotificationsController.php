<?php

namespace SaschaEnde\Users\Controller;

use SaschaEnde\Users\Domain\Repository\NotificationRepository;
use SaschaEnde\Users\Domain\Repository\UserRepository;
use t3h\t3h;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class NotificationsController extends ActionController {

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var NotificationRepository
     */
    protected $notificationRepository;

    public function initializeAction()
    {
        $this->userRepository = t3h::injectClass(UserRepository::class);
        $this->notificationRepository = t3h::injectClass(NotificationRepository::class);
    }

    public function listAction(){

    }

}