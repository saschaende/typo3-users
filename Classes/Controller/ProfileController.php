<?php

namespace SaschaEnde\Users\Controller;

use SaschaEnde\Users\Domain\Model\User;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ProfileController extends ActionController {

    public function showAction(User $user){
        $this->view->assignMultiple([
            'user' => $user
        ]);
    }

}