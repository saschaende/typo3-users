<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Users',
    'description' => 'Users brings you all essential plugins (login, logout, register, forgot password, banlist for spam hosts...) to build a community with your TYPO3 system. Based on easy to customize fluid templates. The extension will be available for TYPO3 9 as soon as the development of all plugins is finished.',
    'category' => 'plugin',
    'author' => 'Filmmusic.io',
    'author_email' => 'info@filmmusic.io',
    'state' => 'beta',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '0.6.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-9.9.99',
            't3helpers' => '0.9.27',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
