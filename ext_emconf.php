<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Users',
    'description' => 'With the extension "users" you get all necessary plugins to build up a community or a protected user area without complicated TYPOSCRIPT. All essential adjustments are made comfortably directly in the plugins. The adaptation is easily done via FLUID templates and is already based on Bootstrap 4. The "users" extension is maintained regularly, errors are fixed within a few days and new functions are added regularly. CREDITS: typo3.net.ua, colorcube.de',
    'category' => 'plugin',
    'author' => 'Sascha Ende',
    'author_email' => '09092020@sascha-ende.de',
    'state' => 'obsolete',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '0.9.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-9.9.99',
            't3helpers' => '0.9.33',
            'static_info_tables'    => '6.7.4',
            'emailthemes' => '1.0.0'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
