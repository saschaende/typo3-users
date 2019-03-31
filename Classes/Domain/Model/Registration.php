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
     * @var bool
     */
    protected $agb = false;

    /**
     * @var bool
     */
    protected $datenschutz = false;

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
     * @return bool
     */
    public function isAgb(): bool {
        return $this->agb;
    }

    /**
     * @param bool $agb
     */
    public function setAgb(bool $agb) {
        $this->agb = $agb;
    }

    /**
     * @return bool
     */
    public function isDatenschutz(): bool {
        return $this->datenschutz;
    }

    /**
     * @param bool $datenschutz
     */
    public function setDatenschutz(bool $datenschutz) {
        $this->datenschutz = $datenschutz;
    }

}