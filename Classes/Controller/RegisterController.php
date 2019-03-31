<?php

namespace SaschaEnde\Users\Controller;

use SaschaEnde\Users\Domain\Model\Registration;
use SaschaEnde\Users\Domain\Model\User;
use SaschaEnde\Users\Domain\Repository\UserRepository;
use t3h\t3h;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserGroupRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

class RegisterController extends ActionController {

    /**
     * @var UserRepository
     */
    protected $frontendUserRepository;

    /**
     * @var FrontendUserGroupRepository
     */
    protected $frontendUserGroupRepository;

    public function initializeAction() {

        $this->frontendUserGroupRepository = $this->objectManager->get(FrontendUserGroupRepository::class);
        $this->frontendUserGroupRepository->setDefaultQuerySettings(t3h::Database()->getQuerySettings());

        $this->frontendUserRepository = $this->objectManager->get(UserRepository::class);

        /** @var Typo3QuerySettings $querysettings */
        $querysettings = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings::class);
        $querysettings->setStoragePageIds([
            $this->settings['usersFolder']
        ]);
        // Für die Überprüfung müssen wir auch inaktive Accounts prüfen
        $querysettings->setIgnoreEnableFields(true);
        $this->frontendUserRepository->setDefaultQuerySettings($querysettings);
    }

    /**
     * @param \SaschaEnde\Users\Domain\Model\Registration $registration
     * @param array $errors
     */
    public function formAction($registration = null, $errors = []) {

        if ($registration == null) {
            $registration = new Registration();
        }

        $this->view->assignMultiple([
            'errors' => $errors,
            'registration' => $registration
        ]);
    }

    /**
     * @param \SaschaEnde\Users\Domain\Model\Registration $registration
     */
    public function submitAction(Registration $registration) {

        $errors = [];

        // Username min 3 and max 20
        if (mb_strlen($registration->getUsername()) < 3 || mb_strlen($registration->getUsername()) > 20) {
            $errors['username'][] = '1';
        }

        // Sonderzeichen im Username
        if (preg_match('/[^a-zA-Z0-9]/', $registration->getUsername()) == 1) {
            $errors['username'][] = '2';
        }

        // Check if username exists
        if ($this->frontendUserRepository->findOneByUsername($registration->getUsername())) {
            $errors['username'][] = '3';
        }

        // No email
        if (empty($registration->getEmail())) {
            $errors['email'][] = '4';
        }

        // E-Mail Format
        if (!filter_var($registration->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $errors['email'][] = '5';
        }

        // Check if email exists
        if ($this->frontendUserRepository->findOneByEmail($registration->getEmail())) {
            $errors['email'][] = '6';
        }

        // Minumum length of password
        if (mb_strlen($registration->getPassword()) < 6) {
            $errors['password'][] = '7';
        }

        // Same passwords
        if ($registration->getPassword() != $registration->getPasswordrepeat()) {
            $errors['password'][] = '8';
        }

        // Errors? So show the form again
        if (count($errors) >= 1) {
            $this->forward(
                'form',
                null,
                null,
                [
                    'registration' => $registration,
                    'errors' => $errors
                ]
            );
        } else {

            // No errors, so add user now
            $user = new User();
            $user->setUsername($registration->getUsername());
            $user->setEmail($registration->getEmail());
            $user->setPassword(t3h::Password()->getHashedPassword($registration->getPassword()));
            $user->setDisable(true);
            $user->setPid($this->settings['usersFolder']);

            // User groups
            foreach (explode(',', $this->settings['userGroups']) as $groupId) {
                $user->addUsergroup($this->frontendUserGroupRepository->findByUid($groupId));
            }

            // Hash
            $registerHash = md5(uniqid() . time());
            $user->setUsersRegisterhash($registerHash);

            $this->frontendUserRepository->add($user);
        }


    }

    public function confirmAction() {

    }

}