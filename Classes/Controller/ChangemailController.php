<?php

namespace SaschaEnde\Users\Controller;

use SaschaEnde\Users\Domain\Repository\UserRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

class ChangemailController extends ActionController {

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
        // Also check for disabled accounts - this is also a verification, if the user reacts to this link via email
        $querysettings->setIgnoreEnableFields(true);
        $this->frontendUserRepository->setDefaultQuerySettings($querysettings);
    }

    public function formAction(){

    }

    public function submitAction(){

    }

}