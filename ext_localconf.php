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
                'Register' => 'form,submit,confirm,redirect,confirmmailchange,confirmdeleteaccount'
            ],
            // non-cacheable actions
            [
                'Register' => 'form,submit,confirm,redirect,confirmmailchange,confirmdeleteaccount'
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

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'SaschaEnde.Users',
            'changeemailconfirm',
            [
                'Changemail' => 'confirm'
            ],
            // non-cacheable actions
            [
                'Changemail' => 'confirm'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'SaschaEnde.Users',
            'changepass',
            [
                'Changepass' => 'form,submit'
            ],
            // non-cacheable actions
            [
                'Changepass' => 'form,submit'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'SaschaEnde.Users',
            'changeprofile',
            [
                'Changeprofile' => 'form,submit'
            ],
            // non-cacheable actions
            [
                'Changeprofile' => 'form,submit'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'SaschaEnde.Users',
            'deleteaccount',
            [
                'Deleteaccount' => 'form,submit'
            ],
            // non-cacheable actions
            [
                'Deleteaccount' => 'form,submit'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'SaschaEnde.Users',
            'deleteaccountconfirm',
            [
                'Deleteaccount' => 'confirm'
            ],
            // non-cacheable actions
            [
                'Deleteaccount' => 'confirm'
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
                        title = [Users] Change email
                        description = Users can change their email adress. The change will be verified via email confirmation.
                        tt_content_defValues {
                            CType = list
                            list_type = users_changeemail
                        }
                    }
                    users_changeemailconfirm {
                        iconIdentifier = users
                        title = [Users] Change email (CONFIRM)
                        description = Put this on a public page, so users can confirm their email change via email
                        tt_content_defValues {
                            CType = list
                            list_type = users_changeemailconfirm
                        }
                    }
                    users_changepass {
                        iconIdentifier = users
                        title = [Users] Change password
                        description = Users can change their password
                        tt_content_defValues {
                            CType = list
                            list_type = users_changepass
                        }
                    }
                    users_changeprofile {
                        iconIdentifier = users
                        title = [Users] Change profile
                        description = Users can change their profile data
                        tt_content_defValues {
                            CType = list
                            list_type = users_changeprofile
                        }
                    }
                    users_deleteaccount {
                        iconIdentifier = users
                        title = [Users] Delete account
                        description = Users can delete account
                        tt_content_defValues {
                            CType = list
                            list_type = users_deleteaccount
                        }
                    }
                    users_deleteaccountconfirm {
                        iconIdentifier = users
                        title = [Users] Delete account (CONFIRM)
                        description = Put this on a public page, so users can confirm their account delete via email
                        tt_content_defValues {
                            CType = list
                            list_type = users_deleteaccountconfirm
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
