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
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'fe_users',
    $temporaryColumns
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'fe_users',
    '--div--;Users,users_lastlogin'
);