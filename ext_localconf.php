<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'SaschaEnde.Users',
            'login',
            [
                'Login' => 'form,login'
            ],
            // non-cacheable actions
            [
                'Login' => 'form,login'
            ]
        );

        // wizards
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            'mod {
            wizards.newContentElement.wizardItems.users {
                header = Users
                elements {
                    users_login {
                        iconIdentifier = users
                        title = [Users] Login
                        description = Login for registered users
                        tt_content_defValues {
                            CType = list
                            list_type = users_login
                        }
                    }
                }
                show = *
            }
       }'
        );
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

        $iconRegistry->registerIcon(
            'users',
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            ['source' => 'EXT:users/Resources/Public/Icons/Extension.png']
        );

    }
);
