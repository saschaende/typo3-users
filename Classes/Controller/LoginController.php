<?php

namespace SaschaEnde\Users\Controller;

use SaschaEnde\Users\Domain\Model\User;
use SaschaEnde\Users\Domain\Repository\UserRepository;
use t3h\t3h;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class LoginController
 * @package SaschaEnde\Users\Controller
 */
class LoginController extends ActionController {

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
     * Show the login form
     */
    public function formAction() {

        // Lets check if the user is logged in?
        if(t3h::FrontendUser()->getCurrentUser()->user && intval($this->settings['redirectIfLogged']) != 0){
            // Redirect
            $uri = t3h::Uri()->getByPid(intval($this->settings['redirectIfLogged']));
            $this->redirectToUri($uri);
        }


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
        /** @var User $user */
        $user = $this->frontendUserRepository->findOneByUsername($arguments['username']);

        // Kein User gefunden, dann versuche es mit der E-Mail Adresse
        if (!$user && $this->settings['allowEmailLogin']) {
            $user = $this->frontendUserRepository->findOneByEmail($arguments['username']);
        }

        // No user found
        if (!$user) {
            $this->redirect('form', null, null, ['error' => 1]);
            return;
        }

        // Check password
        $check = t3h::Password()->checkPassword($arguments['password'], $user->getPassword());

        // Password not correct
        if ($check === false) {
            $this->redirect('form', null, null, ['error' => 1]);
            return;
        }

        // ---------------------------------------------------------------
        // Login success
        // ---------------------------------------------------------------

        // Save last login
        $dt = new \DateTime();
        $user->setUsersLastlogin($dt);

        // Increment login count
        $user->setUsersLogincount($user->getUsersLogincount() + 1);

        // set language
        if ($this->settings['updateLanguage']) {
            $user->setUsersLanguage(t3h::FrontendUser()->getLanguage());
        }


        // update now
        $this->frontendUserRepository->update($user);

        // Login now
        t3h::FrontendUser()->loginUser($user->getUsername());

        // Redirect to redirect action
        $this->redirect('redirect');

    }

    /**
     * Seperate redirect function because protected URIs are not available for unlogged users. Here the user is logged, to the link will be generated.
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function redirectAction() {
        // Redirect
        $uri = t3h::Uri()->getByPid(intval($this->settings['redirectSuccess']));
        $this->redirectToUri($uri);
    }


}