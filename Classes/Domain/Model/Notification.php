<?php
namespace SaschaEnde\Users\Domain\Model;

class Notification extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * type
     * 
     * @var int
     */
    protected $type = 0;

    /**
     * title
     * 
     * @var string
     */
    protected $title = '';

    /**
     * text
     * 
     * @var string
     */
    protected $text = '';

    /**
     * @var int
     */
    protected $pageUid = 0;

    /**
     * link
     * 
     * @var string
     */
    protected $link = '';

    /**
     * flagRead
     * 
     * @var bool
     */
    protected $flagRead = false;

    /**
     * user
     * 
     * @var \SaschaEnde\Users\Domain\Model\User
     */
    protected $user = null;

    /**
     * Returns the type
     * 
     * @return int $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type
     * 
     * @param int $type
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Returns the title
     * 
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     * 
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the text
     * 
     * @return string $text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the text
     * 
     * @param string $text
     * @return void
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return int
     */
    public function getPageUid()
    {
        return $this->pageUid;
    }

    /**
     * @param int $pageUid
     */
    public function setPageUid(int $pageUid)
    {
        $this->pageUid = $pageUid;
    }

    /**
     * Returns the link
     * 
     * @return string $link
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Sets the link
     * 
     * @param string $link
     * @return void
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * Returns the flagRead
     * 
     * @return bool $flagRead
     */
    public function getFlagRead()
    {
        return $this->flagRead;
    }

    /**
     * Sets the flagRead
     * 
     * @param bool $flagRead
     * @return void
     */
    public function setFlagRead($flagRead)
    {
        $this->flagRead = $flagRead;
    }

    /**
     * Returns the boolean state of flagRead
     * 
     * @return bool
     */
    public function isFlagRead()
    {
        return $this->flagRead;
    }

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
}
