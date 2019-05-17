<?php

namespace SaschaEnde\Users\Controller;

use SaschaEnde\Users\Domain\Model\Registration;
use SaschaEnde\Users\Domain\Model\User;
use SaschaEnde\Users\Domain\Repository\UserRepository;
use t3h\t3h;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ChangeprofileController extends ActionController {

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
     * @param \SaschaEnde\Users\Domain\Model\User $user
     * @param array $errors
     */
    public function formAction($user = null, $errors = []) {

        if ($user == null) {
            $user = new User();
        }

        // Setup optionalfields
        $optionalFields = [];
        $requiredFields = explode(',', $this->settings['requiredFields']);
        foreach (explode(',', $this->settings['optionalFields']) as $field) {
            if (in_array($field, $requiredFields)) {
                $required = true;
            } else {
                $required = false;
            }
            $optionalFields[] = [
                'id' => $field,
                'required' => $required
            ];
        }

        $this->view->assignMultiple([
            'user'  => $this->user,
            'errors' => $errors,
            'optionalFields' => $optionalFields
        ]);
    }


    public function submitAction(User $user) {

        $errors = [];

        // ---------------------------------------------------------------------
        // Check required fields

        $requiredFields = explode(',', $this->settings['requiredFields']);
        foreach ($requiredFields as $fieldname) {
            $func = 'get' . GeneralUtility::underscoredToUpperCamelCase($fieldname);
            if (empty($user->$func())) {
                $errors[$fieldname][] = '9';
            }
        }

        // ---------------------------------------------------------------------

        if (count($errors) >= 1) {


            // ---------------------------------------------------------------------
            // ERRORS ... So show the form again
            // ---------------------------------------------------------------------
            $this->forward(
                'form',
                null,
                null,
                [
                    'registration' => $user,
                    'errors' => $errors
                ]
            );
        } else {

            $this->frontendUserRepository->update($user);

        }
    }

}