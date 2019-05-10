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
                'Forgot' => 'form,formsubmit,changeform,changeformsubmit,redirect'
            ],
            // non-cacheable actions
            [
                'Forgot' => 'form,formsubmit,changeform,changeformsubmit,redirect'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'SaschaEnde.Users',
            'register',
            [
                'Register' => 'form,submit,confirm,redirect'
            ],
            // non-cacheable actions
            [
                'Register' => 'form,submit,confirm,redirect'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'SaschaEnde.Users',
            'changeemail',
            [
                'Changemail' => 'form,submit'
            ],
            // non-cacheable actions
            [
                'Changemail' => 'form,submit'
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
                    users_register {
                        iconIdentifier = users
                        title = [Users] Register
                        description = Users can register and sign up for a new account
                        tt_content_defValues {
                            CType = list
                            list_type = users_register
                        }
                    }
                    users_changeemail {
                        iconIdentifier = users
                        title = [Users] Change Email
                        description = Users can change their email adress. The change will be verified via email confirmation.
                        tt_content_defValues {
                            CType = list
                            list_type = users_changeemail
                        }
                    }
                }
                show = *
            }
       }'
        );

        // --------------------------------------------
        // ADD ICON
        // --------------------------------------------

        \t3h\t3h::Icons()->setExtension('users')->add('Extension.png','users');


    }
);
