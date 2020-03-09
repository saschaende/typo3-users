<?php

namespace SaschaEnde\Users\ViewHelpers;

use t3h\t3h;

/**
 * Show fields of the current user
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
        $user = t3h::FrontendUser()->getCurrentUser()->user;
        return $user[$this->arguments['fieldname']];
    }
}
