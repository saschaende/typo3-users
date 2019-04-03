<?php

namespace SaschaEnde\Users\Domain\Model;

class Registration {

    /**
     * @var string
     */
    protected $username = '';

    /**
     * @var string
     */
    protected $email = '';

    /**
     * @var string
     */
    protected $password = '';

    /**
     * @var string
     */
    protected $passwordrepeat = '';

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
     * @var string
     */
    protected $firstName = '';

    /**
     * @var string
     */
    protected $lastName = '';

    /**
     * @var string
     */
    protected $address = '';

    /**
     * @var string
     */
    protected $zip = '';

    /**
     * @var string
     */
    protected $city = '';

    /**
     * @var string
     */
    protected $telephone = '';

    /**
     * @var string
     */
    protected $company = '';


    /**
     * @return string
     *
     */
    public function getUsername(): string {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username) {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email) {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password) {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPasswordrepeat(): string {
        return $this->passwordrepeat;
    }

    /**
     * @param string $passwordrepeat
     */
    public function setPasswordrepeat(string $passwordrepeat) {
        $this->passwordrepeat = $passwordrepeat;
    }

    /**
     * @return string
     */
    public function getUsersConditions() {
        return $this->usersConditions;
    }

    /**
     * @param string $usersConditions
     */
    public function setUsersConditions($usersConditions) {
        $this->usersConditions = $usersConditions;
    }

    /**
     * @return string
     */
    public function getUsersDataprotection() {
        return $this->usersDataprotection;
    }

    /**
     * @param string $usersDataprotection
     */
    public function setUsersDataprotection($usersDataprotection) {
        $this->usersDataprotection = $usersDataprotection;
    }

    /**
     * @return string
     */
    public function getFirstName(): string {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName) {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName) {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getAddress(): string {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address) {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getZip(): string {
        return $this->zip;
    }

    /**
     * @param string $zip
     */
    public function setZip(string $zip) {
        $this->zip = $zip;
    }

    /**
     * @return string
     */
    public function getCity(): string {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city) {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getTelephone(): string {
        return $this->telephone;
    }

    /**
     * @param string $telephone
     */
    public function setTelephone(string $telephone) {
        $this->telephone = $telephone;
    }

    /**
     * @return string
     */
    public function getCompany(): string {
        return $this->company;
    }

    /**
     * @param string $company
     */
    public function setCompany(string $company) {
        $this->company = $company;
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

}