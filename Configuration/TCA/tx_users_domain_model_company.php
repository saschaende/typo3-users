<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:users/Resources/Private/Language/locallang_db.xlf:tx_users_domain_model_company',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => false,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,street,zip,city,country,holding',
        'iconfile' => 'EXT:users/Resources/Public/Icons/Extension.png'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, street, zip, city, country, holding',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, street, zip, city, country, holding, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [

        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf:labels.enabled'
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'behaviour' => [
                'allowLanguageSynchronization' => true
            ],
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'behaviour' => [
                'allowLanguageSynchronization' => true
            ],
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
            ],
        ],

        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:users/Resources/Private/Language/locallang_db.xlf:tx_users_domain_model_company.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'street' => [
            'exclude' => true,
            'label' => 'LLL:EXT:users/Resources/Private/Language/locallang_db.xlf:tx_users_domain_model_company.street',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'zip' => [
            'exclude' => true,
            'label' => 'LLL:EXT:users/Resources/Private/Language/locallang_db.xlf:tx_users_domain_model_company.zip',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'city' => [
            'exclude' => true,
            'label' => 'LLL:EXT:users/Resources/Private/Language/locallang_db.xlf:tx_users_domain_model_company.city',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'country' => [
            'exclude' => true,
            'label' => 'LLL:EXT:users/Resources/Private/Language/locallang_db.xlf:tx_users_domain_model_company.country',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'holding' => [
            'exclude' => true,
            'label' => 'LLL:EXT:users/Resources/Private/Language/locallang_db.xlf:tx_users_domain_model_company.holding',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_users_domain_model_holding',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
    
    ],
];
