<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Users',
    'description' => 'Community essentials for TYPO3: Login, register, forgot pass .... (still in development)',
    'category' => 'plugin',
    'author' => '',
    'author_email' => '',
    'state' => 'alpha',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '0.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-8.7.99',
            't3helpers' => '0.9.5',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
