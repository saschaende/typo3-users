<?php

namespace SaschaEnde\Users\Controller;

use SaschaEnde\Users\Domain\Model\User;
use SaschaEnde\Users\Domain\Repository\UserRepository;
use t3h\t3h;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserGroupRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class LoginController
 * @package SaschaEnde\Users\Controller
 */
class LoginController extends ActionController {

    protected $user = null;

    /**
     * @var UserRepository
     */
    protected $frontendUserRepository;

    /**
     * @var FrontendUserGroupRepository
     */
    protected $frontendUserGroupRepository;

    public function initializeAction() {
        $this->frontendUserRepository = $this->objectManager->get(UserRepository::class);
        $this->frontendUserGroupRepository = $this->objectManager->get(FrontendUserGroupRepository::class);
        $this->frontendUserGroupRepository->setDefaultQuerySettings(t3h::Database()->getQuerySettings());

        /** @var Typo3QuerySettings $querysettings */
        $querysettings = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings::class);
        $querysettings->setStoragePageIds([
            $this->settings['usersFolder']
        ]);
        $this->frontendUserRepository->setDefaultQuerySettings($querysettings);

        // Load user object
        if (t3h::FrontendUser()->getCurrentUser()->user) {
            $this->user = $this->frontendUserRepository->findByUid(t3h::FrontendUser()->getCurrentUser()->user['uid']);
        }
    }

    /**
     * Show the login form
     */
    public function formAction() {

        // Lets check if the user is logged in?
        if (t3h::FrontendUser()->isLogged() && intval($this->settings['redirectIfLogged']) != 0) {
            // Redirect
            $uri = t3h::Uri()->getByPid(intval($this->settings['redirectIfLogged']));
            $this->redirectToUri($uri);
        }

        $arguments = $this->request->getArguments();
        $this->view->assignMultiple([
            'error' => intval($arguments['error']),
            'user' => $this->user
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


        // Check if user has one of the allowed usergroups (is setting is set)
        $usergroups = explode(',', $this->settings['allowedUsergroups']);
        $usergroups = array_filter($usergroups); // Remove empty fields
        if (count($usergroups) >= 1) {
            $groupAllowed = false;
            foreach ($usergroups as $groupId) {
                // Load the usergroup
                $groupObj = $this->frontendUserGroupRepository->findOneByUid($groupId);
                // Check, if the user contains this usergroup
                if ($user->getUsergroup()->contains($groupObj)) {
                    // If we found one, we can abort here and set to true
                    $groupAllowed = true;
                    break;
                }
            }
            if ($groupAllowed === false) {
                $this->redirect('form', null, null, ['error' => 2]);
                return;
            }
        }


        // ---------------------------------------------------------------
        // Login success
        // ---------------------------------------------------------------

        /** @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher */
        $signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
        $signalSlotDispatcher->dispatch(__CLASS__, 'beforeLoginSuccess', [$user, $this]);

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

        /** @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher */
        $signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
        $signalSlotDispatcher->dispatch(__CLASS__, 'afterLoginSuccess', [$user, $this]);

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
