<?php

namespace SaschaEnde\Users\Flexform;
use t3h\t3h;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class FlexFormUserFunc
 */
class Fields {

    /**
     * @param array $fConfig
     *
     * @return void
     */
    public function register(&$fConfig) {

        $config = t3h::Settings()->getExtension('tx_users');

        foreach ($config['fields.'] as $key=>$value){
            // push it into the config array
            array_push($fConfig['items'], [
                $value['name'],
                $value['key']
            ]);
        }

    }

    /**
     * @param array $fConfig
     *
     * @return void
     */
    public function changeprofile(&$fConfig) {

        $config = t3h::Settings()->getExtension('tx_users');

        // push it into the config array
        if($fConfig['field'] == 'settings.optionalFields'){
            array_push($fConfig['items'], [
                'Username',
                'username'
            ]);
        }

        foreach ($config['fields.'] as $key=>$value){
            // push it into the config array
            array_push($fConfig['items'], [
                $value['name'],
                $value['key']
            ]);
        }

    }
}