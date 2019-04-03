<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'SaschaEnde.Users',
            'login',
            '[Users] Login'
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'SaschaEnde.Users',
            'logout',
            '[Users] Logout'
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'SaschaEnde.Users',
            'forgot',
            '[Users] Forgot password'
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'SaschaEnde.Users',
            'register',
            '[Users] Register'
        );

        // --------------------------------------------
        // TYPOSCRIPT
        // --------------------------------------------

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('users', 'Configuration/TypoScript', 'Users');

        // --------------------------------------------
        // TABLES
        // --------------------------------------------

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_users_domain_model_bannedhosts', 'EXT:users/Resources/Private/Language/locallang_csh_tx_users_domain_model_bannedhosts.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_users_domain_model_bannedhosts');

        // --------------------------------------------
        // FLEXFORM
        // --------------------------------------------

        \t3h\t3h::Inject()->setExtension('users')->addFlexform('login');
        \t3h\t3h::Inject()->setExtension('users')->addFlexform('logout');
        \t3h\t3h::Inject()->setExtension('users')->addFlexform('forgot');
        \t3h\t3h::Inject()->setExtension('users')->addFlexform('register');

    }
);