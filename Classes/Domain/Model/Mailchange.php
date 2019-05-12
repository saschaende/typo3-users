<?php

namespace SaschaEnde\Users\Domain\Model;

class Mailchange {

    /**
     * @var string
     */
    protected $email = '';

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }


}