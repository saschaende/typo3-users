<?php

namespace SaschaEnde\Users\Controller;

use t3h\t3h;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

class LoginController extends ActionController {

    /**
     * @var FrontendUserRepository
     */
    protected $frontendUserRepository;

    public function initializeAction() {
        $this->frontendUserRepository = $this->objectManager->get(FrontendUserRepository::class);

        /** @var Typo3QuerySettings $querysettings */
        $querysettings = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings::class);
        $querysettings->setStoragePageIds([
            $this->settings['usersFolder']
        ]);
        $this->frontendUserRepository->setDefaultQuerySettings($querysettings);
    }

    /**
     * Show the login form
     */
    public function formAction() {
        $arguments = $this->request->getArguments();
        $this->view->assignMultiple([
            'error' => intval($arguments['error'])
        ]);
    }

    /**
     * Do login
     * @throws \ReflectionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function loginAction() {
        $arguments = $this->request->getArguments();

        if (empty($arguments['username']) || empty($arguments['password'])) {
            $this->redirect('form', null, null, ['error' => 1]);
            return;
        }

        // Load userdata
        /** @var FrontendUser $user */
        $user = $this->frontendUserRepository->findOneByUsername($arguments['username']);

        // Kein User gefunden, dann versuche es mit der E-Mail Adresse
        if (!$user) {
            $user = $this->frontendUserRepository->findOneByEmail($arguments['username']);
        }

        if (!$user) {
            $this->redirect('form', null, null, ['error' => 1]);
            return;
        }

        // Check password
        $check = t3h::Password()->checkPassword($arguments['password'], $user->getPassword());

        if ($check === false) {
            $this->redirect('form', null, null, ['error' => 1]);
            return;
        }

        // ---------------------------------------------------------------
        // Login erfolgreich
        // ---------------------------------------------------------------

        // Login
        t3h::FrontendUser()->loginUser($arguments['username']);

        // Redirect
        $uri = t3h::Uri()->getByPid($this->settings['redirectSuccess']);
        $this->redirectToUri($uri);
    }


}