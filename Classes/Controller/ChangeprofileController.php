<?php

namespace SaschaEnde\Users\Controller;

use SaschaEnde\Users\Domain\Model\User;
use SaschaEnde\Users\Domain\Repository\UserRepository;
use SJBR\StaticInfoTables\Domain\Repository\CountryRepository;
use t3h\t3h;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

class ChangeprofileController extends ActionController {

    /**
     * @var CountryRepository
     */
    protected $countryRepository;

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

        $this->countryRepository = $this->objectManager->get(CountryRepository::class);
        $this->countryRepository->setDefaultOrderings(['shortNameEn'=>QueryInterface::ORDER_ASCENDING]);

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
        $requiredFields = array_filter($requiredFields); // Remove empty fields
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
            'user' => $this->user,
            'errors' => $errors,
            'optionalFields' => $optionalFields,
            'countries' => $this->countryRepository->findAll()
        ]);
    }


    public function submitAction(User $user) {

        $errors = [];

        // Username min 3 and max 20
        if (mb_strlen($user->getUsername()) < 3 || mb_strlen($user->getUsername()) > 20) {
            $errors['username'][] = '1';
        }

        // Sonderzeichen im Username
        if (preg_match('/[^a-zA-Z0-9]/', $user->getUsername()) == 1) {
            $errors['username'][] = '2';
        }

        // Check if username exists and if its not our username
        if ($userfound = $this->frontendUserRepository->findOneByUsername($user->getUsername())) {
            if ($userfound->getUid() != $user->getUid()) {
                $errors['username'][] = '3';
            }
        }

        // ---------------------------------------------------------------------
        // Check required fields

        $requiredFields = explode(',', $this->settings['requiredFields']);
        $requiredFields = array_filter($requiredFields); // Remove empty fields
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