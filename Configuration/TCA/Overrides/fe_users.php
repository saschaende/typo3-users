<?php

$temporaryColumns = [
    'users_lastlogin' => [
        'exclude' => 1,
        'label' => 'Last login',
        'config' => [
            'type' => 'input',
            'size' => '13',
            'eval' => 'datetime',
            'default' => '0',
        ],
    ],
    'users_logincount' => [
        'exclude' => true,
        'label' => 'Number of logins',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'eval' => 'trim'
        ],
    ],
    'users_forgothash' => [
        'exclude' => true,
        'label' => 'Forgot hash',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'eval' => 'trim'
        ],
    ],
    'users_forgothash_valid' => [
        'exclude' => 1,
        'label' => 'Forgot hash is valid until',
        'config' => [
            'type' => 'input',
            'size' => '13',
            'eval' => 'datetime',
            'default' => '0',
        ],
    ],
    'users_registerhash' => [
        'exclude' => true,
        'label' => 'Register hash',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'eval' => 'trim'
        ],
    ],
    'disabled' => [
        'exclude' => true,
        'label' => 'Disabled',
        'config' => [
            'type' => 'check',
            'items' => [
                '1' => [
                    '0' => 'Yes'
                ]
            ],
        ],
    ],
    'users_conditions' => [
        'exclude' => true,
        'label' => 'Conditions (AGB)',
        'config' => [
            'type' => 'check',
            'items' => [
                '1' => [
                    '0' => 'Yes'
                ]
            ],
        ],
    ],
    'users_dataprotection' => [
        'exclude' => true,
        'label' => 'Data protection (Datenschutz)',
        'config' => [
            'type' => 'check',
            'items' => [
                '1' => [
                    '0' => 'Yes'
                ]
            ],
        ],
    ],
    'users_newsletter' => [
        'exclude' => true,
        'label' => 'Newsletter',
        'config' => [
            'type' => 'check',
            'items' => [
                '1' => [
                    '0' => 'Yes'
                ]
            ],
        ],
    ],
    'users_website' => [
        'exclude' => 1,
        'label' => 'Registered on this website',
        'config' => [
            'type' => 'group',
            'internal_type' => 'db',
            'allowed' => 'pages',
            'size' => '1',
            'maxitems' => '1',
            'minitems' => '0',
            'show_thumbs' => '1',
        ],
    ],
    'users_language' => [
        'exclude' => 1,
        'label' => 'Language',
        'config' => [
            'type' => 'group',
            'internal_type' => 'db',
            'allowed' => 'sys_language',
            'size' => '1',
            'maxitems' => '1',
            'minitems' => '0',
            'show_thumbs' => '1',
            'default'   => '0'
        ],
    ],
    'users_newemail' => [
        'exclude' => true,
        'label' => 'New email (if user wants to change his email)',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'eval' => 'trim'
        ],
    ],
    'users_newemailhash' => [
        'exclude' => true,
        'label' => 'Email hash (if users wants to change email)',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'eval' => 'trim'
        ],
    ],
    'users_deletehash' => [
        'exclude' => true,
        'label' => 'Delete account hash',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'eval' => 'trim'
        ],
    ],
    'users_deletehash_valid' => [
        'exclude' => 1,
        'label' => 'Delete account hash is valid until',
        'config' => [
            'type' => 'input',
            'size' => '13',
            'eval' => 'datetime',
            'default' => '0',
        ],
    ],

];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'fe_users',
    $temporaryColumns
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'fe_users',
    '--div--;Users,users_lastlogin,users_logincount,users_forgothash,users_forgothash_valid,users_registerhash,users_newemail,users_newemailhash,users_conditions,users_dataprotection,users_newsletter,users_website,users_language,users_deletehash,users_deletehash_valid'
);