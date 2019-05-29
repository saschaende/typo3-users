<?php
namespace SaschaEnde\Users\Domain\Model;

class BannedMails extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * host
     *
     * @var string
     */
    protected $email = '';

    /**
     * @var string
     */
    protected $reason = '';

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
    public function getReason(): string {
        return $this->reason;
    }

    /**
     * @param string $reason
     */
    public function setReason(string $reason) {
        $this->reason = $reason;
    }

}
