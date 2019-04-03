<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Users',
    'description' => '[STILL IN DEVELOPMENT] Users will bring you all essential plugins to build a community with your TYPO3 system. Based on easy to customize fluid templates. The extension will be available for TYPO3 9 as soon as the development is finished.',
    'category' => 'plugin',
    'author' => 'Filmmusic.io',
    'author_email' => 'info@filmmusic.io',
    'state' => 'alpha',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '0.6.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-8.9.99',
            't3helpers' => '0.9.24',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
