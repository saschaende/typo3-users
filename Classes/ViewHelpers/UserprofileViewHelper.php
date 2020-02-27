<?php

namespace SaschaEnde\Users\ViewHelpers;

use SaschaEnde\Users\Domain\Model\User;
use t3h\t3h;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

/**
 * ViewHelper to generate Links to Userprofiles from anywhere
 * {namespace users=SaschaEnde\Users\ViewHelpers}
 * Example:
 * <users:userprofile userId="{user.uid}">Visit profile</users:userprofile>
 */
class UserprofileViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    use CompileWithContentArgumentAndRenderStatic;

    /**
     * @var boolean
     */
    protected $escapeChildren = false;

    /**
     * @var boolean
     */
    protected $escapeOutput = false;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('userId', 'int', 'User ID', true);
        $this->registerArgument('class', 'string', 'CSS Class', false);
        $this->registerArgument('id', 'string', 'ID', false);
    }

    public function render()
    {
        // Get Settings
        $profilePid = t3h::Settings()->getExtension('tx_users')['pages.']['profile'];

        // Generate Link to profile
        $link = t3h::Uri()->getByAction(
            $profilePid,
            'tx_users_profile',
            'Profile',
            'show',
            ['user' => $this->arguments['userId']]
        );

        // Return output
        $content = '<a href="' . $link . '" class="' . $this->arguments['class'] . '" id="' . $this->arguments['id'] . '">' . $this->renderChildren() . '</a>';
        return $content;
    }
}
