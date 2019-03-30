<?php
namespace SaschaEnde\Users\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;

class User extends FrontendUser {

    /**
     * @var \DateTime
     */
    protected $usersLastlogin;

    /**
     * @var string
     */
    protected $usersForgothash = '';

    /**
     * @var \DateTime
     */
    protected $usersForgothashValid;

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

    /**
     * @return string
     */
    public function getUsersForgothash(): string {
        return $this->usersForgothash;
    }

    /**
     * @param string $usersForgothash
     */
    public function setUsersForgothash(string $usersForgothash) {
        $this->usersForgothash = $usersForgothash;
    }

    /**
     * @return \DateTime
     */
    public function getUsersForgothashValid() {
        return $this->usersForgothashValid;
    }

    /**
     * @param \DateTime $usersForgothashValid
     */
    public function setUsersForgothashValid(\DateTime $usersForgothashValid) {
        $this->usersForgothashValid = $usersForgothashValid;
    }

}