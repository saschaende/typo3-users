<?php

namespace SaschaEnde\Users\Controller;

use t3h\t3h;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class LogoutController extends ActionController {

    public function logoutAction() {
        $uri = t3h::Uri()->getByPid(
            intval($this->settings['redirectSuccess']),
            false,
            true,
            ['logintype' => 'logout']
        );
        $this->redirectToUri($uri);
    }


}