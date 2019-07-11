<?php

$temporaryColumns = [
    'users_dashboard_image' => [
        'exclude' => 1,
        'label' => 'Image',
        'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
            'video_image',
            [
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                ],
                'foreign_types' => [
                    '0' => [
                        'showitem' => '
                    --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                    --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                        'showitem' => '
                    --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                    --palette--;;filePalette'
                    ],
                ],
                'maxitems' => 1
            ],
            $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
        ),
    ],
    'users_dashboard_title' => [
        'exclude' => 1,
        'label' => 'Title',
        'config' => [
            'type' => 'input',
            'size' => '60',
        ],
    ],
    'users_dashboard_description' => [
        'exclude' => 1,
        'label' => 'Description',
        'config' => [
            'type' => 'text',
            'cols' => 40,
            'rows' => 15,
            'eval' => 'trim',
            'default'   => ''
        ],
        'defaultExtras' => 'richtext:rte_transform[mode=ts_css]'
    ],
    'users_dashboard_button' => [
        'exclude' => 1,
        'label' => 'Button link text',
        'config' => [
            'type' => 'input',
            'size' => '60',
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'pages',
    $temporaryColumns
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    '--div--;Dashboard (users), users_dashboard_image,users_dashboard_title,users_dashboard_description,users_dashboard_button',
    '',
    'after:categories'
);