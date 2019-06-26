<?php

namespace SaschaEnde\Users\Controller;

use SaschaEnde\Users\Domain\Model\Pages;
use SaschaEnde\Users\Domain\Model\User;
use SaschaEnde\Users\Domain\Repository\PagesRepository;
use SaschaEnde\Users\Domain\Repository\UserRepository;
use t3h\t3h;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class DashboardController extends ActionController {

    /**
     * @var User
     */
    protected $user;

    /**
     * @var UserRepository
     */
    protected $frontendUserRepository;

    /**
     * @var PagesRepository
     */
    protected $pagesRepository;

    public function initializeAction() {
        $this->frontendUserRepository = t3h::injectClass(UserRepository::class);
        $this->user = $this->frontendUserRepository->findByUid(t3h::FrontendUser()->getCurrentUser()->user['uid']);
        $this->pagesRepository = t3h::injectClass(PagesRepository::class);
    }

    public function listAction() {

        // Get them all once
        $this->pagesRepository->setPage($this->settings['pages']);
        $pages = $this->pagesRepository->getResults();

        // Lets sort them now
        $pagesByUid = [];
        foreach ($pages as $page) {
            /** @var Pages $page */
            $pagesByUid[$page->getUid()] = $page;
        }

        // Make an array for output with the right order
        $pagesOutput = [];
        foreach (explode(',', $this->settings['pages']) as $uid) {
            $pagesOutput[] = $pagesByUid[$uid];
        }

        // Show template
        $this->view->assignMultiple([
            'user' => $this->user,
            'pages' => $pagesOutput
        ]);
    }

}