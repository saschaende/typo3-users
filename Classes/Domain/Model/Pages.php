<?php
/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2017 Sascha Ende <s.ende@pixelcreation.de>, pixelcreation GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace SaschaEnde\Users\Domain\Model;

use \TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Pages extends AbstractEntity {

    /**
     * @var int
     */
    protected $uid;

    /**
     * @var int
     * @lazy
     */
    protected $pid;

    /**
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    protected $usersDashboardImage = null;

    /**
     * @var string
     */
    protected $usersDashboardTitle = '';

    /**
     * @var string
     */
    protected $usersDashboardDescription = '';

    /**
     * @return int
     */
    public function getUid(): int {
        return $this->uid;
    }

    /**
     * @param int $uid
     */
    public function setUid($uid) {
        $this->uid = $uid;
    }

    /**
     * @return int
     */
    public function getPid(): int {
        return $this->pid;
    }

    /**
     * @param int $pid
     */
    public function setPid($pid) {
        $this->pid = $pid;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    public function getUsersDashboardImage() {
        return $this->usersDashboardImage;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $usersDashboardImage
     */
    public function setUsersDashboardImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $usersDashboardImage) {
        $this->usersDashboardImage = $usersDashboardImage;
    }

    /**
     * @return string
     */
    public function getUsersDashboardTitle() {
        return $this->usersDashboardTitle;
    }

    /**
     * @param string $usersDashboardTitle
     */
    public function setUsersDashboardTitle($usersDashboardTitle) {
        $this->usersDashboardTitle = $usersDashboardTitle;
    }

    /**
     * @return string
     */
    public function getUsersDashboardDescription() {
        return $this->usersDashboardDescription;
    }

    /**
     * @param string $usersDashboardDescription
     */
    public function setUsersDashboardDescription($usersDashboardDescription) {
        $this->usersDashboardDescription = $usersDashboardDescription;
    }

}