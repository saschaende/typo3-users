<?php
namespace SaschaEnde\Users\Domain\Model;

/***
 *
 * This file is part of the "Users News" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019
 *
 ***/

/**
 * NewsBookmark
 */
class NewsBookmark extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * user
     *
     * @var \SaschaEnde\Users\Domain\Model\User
     */
    protected $user = null;

    /**
     * news
     *
     * @var \SaschaEnde\Users\Domain\Model\News
     */
    protected $news = null;

    /**
     * Returns the user
     *
     * @return \SaschaEnde\Users\Domain\Model\User $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Sets the user
     *
     * @param \SaschaEnde\Users\Domain\Model\User $user
     * @return void
     */
    public function setUser(\SaschaEnde\Users\Domain\Model\User $user)
    {
        $this->user = $user;
    }

    /**
     * Returns the news
     *
     * @return \SaschaEnde\Users\Domain\Model\News $news
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Sets the news
     *
     * @param \SaschaEnde\Users\Domain\Model\News $news
     * @return void
     */
    public function setNews(\SaschaEnde\Users\Domain\Model\News $news)
    {
        $this->news = $news;
    }
}
