<?php

namespace SaschaEnde\Users\ViewHelpers;

/**
 * {namespace users=SaschaEnde\Users\ViewHelpers}
 */
class UserViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

    /**
     * @return void
     */
    public function initializeArguments() {
        $this->registerArgument('fieldname', 'string', 'Name of the field to output');
    }

    public function render() {
        $user = $GLOBALS['TSFE']->fe_user->user;
        return $user[$this->arguments['fieldname']];
    }
}
