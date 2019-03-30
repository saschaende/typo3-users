<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'SaschaEnde.Users',
            'login',
            [
                'Login' => 'form,login,redirect'
            ],
            // non-cacheable actions
            [
                'Login' => 'form,login,redirect'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'SaschaEnde.Users',
            'logout',
            [
                'Logout' => 'logout'
            ],
            // non-cacheable actions
            [
                'Login' => 'logout'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'SaschaEnde.Users',
            'forgot',
            [
                'Forgot' => 'form,formsubmit,changeform,changeformsubmit'
            ],
            // non-cacheable actions
            [
                'Forgot' => 'form,formsubmit,changeform,changeformsubmit'
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
                    users_logout {
                        iconIdentifier = users
                        title = [Users] Logout
                        description = Logout for registered users
                        tt_content_defValues {
                            CType = list
                            list_type = users_logout
                        }
                    }
                    users_forgot {
                        iconIdentifier = users
                        title = [Users] Forgot password
                        description = Users can set a new password via email and link
                        tt_content_defValues {
                            CType = list
                            list_type = users_forgot
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
