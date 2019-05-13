<?php

namespace SaschaEnde\Users\Domain\Model;

class Passwordchange {

    /**
     * @var string
     */
    protected $password = '';

    /**
     * @var string
     */
    protected $repeat = '';

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
    public function getRepeat(): string {
        return $this->repeat;
    }

    /**
     * @param string $repeat
     */
    public function setRepeat(string $repeat) {
        $this->repeat = $repeat;
    }

}