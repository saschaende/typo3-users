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
     * @var string
     */
    protected $usersConditions = '';

    /**
     * @var string
     */
    protected $usersDataprotection = '';

    /**
     * @var string
     */
    protected $usersNewsletter = '';

    /**
     * @var int
     */
    protected $usersWebsite = 0;

    /**
     * @var int
     */
    protected $usersLanguage = 0;

    /**
     * @var string
     */
    protected $usersNewemail = '';

    /**
     * @var string
     */
    protected $usersNewemailhash = '';

    /**
     * @var string
     */
    protected $usersDeletehash = '';

    /**
     * @var \DateTime
     */
    protected $usersDeletehashValid;

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

    /**
     * @return string
     */
    public function getUsersConditions(): string {
        return $this->usersConditions;
    }

    /**
     * @param string $usersConditions
     */
    public function setUsersConditions(string $usersConditions) {
        $this->usersConditions = $usersConditions;
    }

    /**
     * @return string
     */
    public function getUsersDataprotection(): string {
        return $this->usersDataprotection;
    }

    /**
     * @param string $usersDataprotection
     */
    public function setUsersDataprotection(string $usersDataprotection) {
        $this->usersDataprotection = $usersDataprotection;
    }

    /**
     * @return string
     */
    public function getUsersNewsletter(): string {
        return $this->usersNewsletter;
    }

    /**
     * @param string $usersNewsletter
     */
    public function setUsersNewsletter(string $usersNewsletter) {
        $this->usersNewsletter = $usersNewsletter;
    }

    /**
     * @return int
     */
    public function getUsersWebsite(): int {
        return $this->usersWebsite;
    }

    /**
     * @param int $usersWebsite
     */
    public function setUsersWebsite(int $usersWebsite) {
        $this->usersWebsite = $usersWebsite;
    }

    /**
     * @return int
     */
    public function getUsersLanguage(): int {
        return $this->usersLanguage;
    }

    /**
     * @param int $usersLanguage
     */
    public function setUsersLanguage(int $usersLanguage) {
        $this->usersLanguage = $usersLanguage;
    }

    /**
     * @return string
     */
    public function getUsersNewemail(): string {
        return $this->usersNewemail;
    }

    /**
     * @param string $usersNewemail
     */
    public function setUsersNewemail(string $usersNewemail) {
        $this->usersNewemail = $usersNewemail;
    }

    /**
     * @return string
     */
    public function getUsersNewemailhash(): string {
        return $this->usersNewemailhash;
    }

    /**
     * @param string $usersNewemailhash
     */
    public function setUsersNewemailhash(string $usersNewemailhash) {
        $this->usersNewemailhash = $usersNewemailhash;
    }

    /**
     * @return string
     */
    public function getUsersDeletehash(): string {
        return $this->usersDeletehash;
    }

    /**
     * @param string $usersDeletehash
     */
    public function setUsersDeletehash(string $usersDeletehash) {
        $this->usersDeletehash = $usersDeletehash;
    }

    /**
     * @return \DateTime
     */
    public function getUsersDeletehashValid(): \DateTime {
        return $this->usersDeletehashValid;
    }

    /**
     * @param \DateTime $usersDeletehashValid
     */
    public function setUsersDeletehashValid($usersDeletehashValid) {
        $this->usersDeletehashValid = $usersDeletehashValid;
    }

}