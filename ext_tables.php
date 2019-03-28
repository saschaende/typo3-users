<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'SaschaEnde.Users',
            'login',
            '[Users] Login'
        );

        // --------------------------------------------
        // FLEXFORM
        // --------------------------------------------

        \t3h\t3h::Inject()->setExtension('users')->addFlexform('login');

    }
);