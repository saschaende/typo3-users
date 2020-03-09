<?php

namespace SaschaEnde\Users\Controller;

use t3h\t3h;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class LogoutController extends ActionController {

    public function logoutAction() {

        $user = t3h::FrontendUser()->getCurrentUser()->user;

        $uri = t3h::Uri()->getByPid(
            intval($this->settings['redirectSuccess']),
            false,
            true,
            ['logintype' => 'logout']
        );

        /** @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher */
        $signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
        $signalSlotDispatcher->dispatch(__CLASS__, 'afterLogoutSuccess', [$user,$this]);

        $this->redirectToUri($uri);
    }


}