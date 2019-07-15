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
 * News
 */
class News extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * content
     *
     * @var string
     */
    protected $content = '';

    /**
     * shortcontent
     *
     * @var string
     */
    protected $shortcontent = '';

    /**
     * image
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @cascade remove
     */
    protected $image = null;

    /**
     * highlight
     *
     * @var bool
     */
    protected $highlight = false;

    /**
     * categories
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\SaschaEnde\Users\Domain\Model\NewsCategory>
     */
    protected $categories = null;

    /**
     * company
     *
     * @var \SaschaEnde\Users\Domain\Model\Company
     */
    protected $company = null;

    /**
     * user
     *
     * @var \SaschaEnde\Users\Domain\Model\User
     */
    protected $user = null;

    /**
     * groups
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup>
     */
    protected $groups = null;

    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->categories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->groups = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
     * Returns the content
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets the content
     *
     * @param string $content
     * @return void
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Returns the shortcontent
     *
     * @return string $shortcontent
     */
    public function getShortcontent()
    {
        return $this->shortcontent;
    }

    /**
     * Sets the shortcontent
     *
     * @param string $shortcontent
     * @return void
     */
    public function setShortcontent($shortcontent)
    {
        $this->shortcontent = $shortcontent;
    }

    /**
     * Returns the image
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Sets the image
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     * @return void
     */
    public function setImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image)
    {
        $this->image = $image;
    }

    /**
     * Adds a NewsCategory
     *
     * @param \SaschaEnde\Users\Domain\Model\NewsCategory $category
     * @return void
     */
    public function addCategory(\SaschaEnde\Users\Domain\Model\NewsCategory $category)
    {
        $this->categories->attach($category);
    }

    /**
     * Removes a NewsCategory
     *
     * @param \SaschaEnde\Users\Domain\Model\NewsCategory $categoryToRemove The NewsCategory to be removed
     * @return void
     */
    public function removeCategory(\SaschaEnde\Users\Domain\Model\NewsCategory $categoryToRemove)
    {
        $this->categories->detach($categoryToRemove);
    }

    /**
     * Returns the categories
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\SaschaEnde\Users\Domain\Model\NewsCategory> $categories
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Sets the categories
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\SaschaEnde\Users\Domain\Model\NewsCategory> $categories
     * @return void
     */
    public function setCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Returns the company
     *
     * @return \SaschaEnde\Users\Domain\Model\Company $company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Sets the company
     *
     * @param \SaschaEnde\Users\Domain\Model\Company $company
     * @return void
     */
    public function setCompany(\SaschaEnde\Users\Domain\Model\Company $company)
    {
        $this->company = $company;
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

    /**
     * Adds a FrontendUserGroup
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup $group
     * @return void
     */
    public function addGroup(\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup $group)
    {
        $this->groups->attach($group);
    }

    /**
     * Removes a FrontendUserGroup
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup $groupToRemove The FrontendUserGroup to be removed
     * @return void
     */
    public function removeGroup(\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup $groupToRemove)
    {
        $this->groups->detach($groupToRemove);
    }

    /**
     * Returns the groups
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup> $groups
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Sets the groups
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup> $groups
     * @return void
     */
    public function setGroups(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $groups)
    {
        $this->groups = $groups;
    }

    /**
     * Returns the highlight
     *
     * @return bool $highlight
     */
    public function getHighlight()
    {
        return $this->highlight;
    }

    /**
     * Sets the highlight
     *
     * @param bool $highlight
     * @return void
     */
    public function setHighlight($highlight)
    {
        $this->highlight = $highlight;
    }

    /**
     * Returns the boolean state of highlight
     *
     * @return bool
     */
    public function isHighlight()
    {
        return $this->highlight;
    }
}
