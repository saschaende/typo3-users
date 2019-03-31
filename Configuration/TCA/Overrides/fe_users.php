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
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'fe_users',
    $temporaryColumns
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'fe_users',
    '--div--;Users,users_lastlogin,users_logincount,users_forgothash,users_forgothash_valid,users_registerhash'
);