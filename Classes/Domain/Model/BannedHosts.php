<?php
namespace SaschaEnde\Users\Domain\Model;

/***
 *
 * This file is part of the "Users" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019
 *
 ***/

/**
 * BannedHosts
 */
class BannedHosts extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * host
     *
     * @var string
     */
    protected $host = '';

    /**
     * Returns the host
     *
     * @return string $host
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Sets the host
     *
     * @param string $host
     * @return void
     */
    public function setHost($host)
    {
        $this->host = $host;
    }
}
