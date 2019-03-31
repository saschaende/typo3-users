<?php
namespace SaschaEnde\Users\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;

class User extends FrontendUser {

    /**
     * @var \DateTime
     */
    protected $usersLastlogin;

    /**
     * @var int
     */
    protected $usersLogincount = 0;

    /**
     * @var string
     */
    protected $usersForgothash = '';

    /**
     * @var \DateTime
     */
    protected $usersForgothashValid;

    /**
     * @var string
     */
    protected $usersRegisterhash = '';

    /**
     * @var bool
     */
    protected $disable = false;

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
     * @return int
     */
    public function getUsersLogincount(): int {
        return $this->usersLogincount;
    }

    /**
     * @param int $usersLogincount
     */
    public function setUsersLogincount(int $usersLogincount) {
        $this->usersLogincount = $usersLogincount;
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
    public function setUsersForgothashValid($usersForgothashValid) {
        $this->usersForgothashValid = $usersForgothashValid;
    }

    /**
     * @return string
     */
    public function getUsersRegisterhash(): string {
        return $this->usersRegisterhash;
    }

    /**
     * @param string $usersRegisterhash
     */
    public function setUsersRegisterhash(string $usersRegisterhash) {
        $this->usersRegisterhash = $usersRegisterhash;
    }

    /**
     * @return bool
     */
    public function isDisable(): bool {
        return $this->disable;
    }

    /**
     * @param bool $disable
     */
    public function setDisable(bool $disable) {
        $this->disable = $disable;
    }

}