<?php
namespace SaschaEnde\Users\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;

class User extends FrontendUser {

    /**
     * @var \DateTime
     */
    protected $usersLastlogin;

    /**
     * @return \DateTime
     */
    public function getUsersLastlogin() {
        return $this->usersLastlogin;
    }

    /**
     * @param \DateTime $usersLastlogin
     */
    public function setUsersLastlogin(\DateTime $usersLastlogin) {
        $this->usersLastlogin = $usersLastlogin;
    }

}