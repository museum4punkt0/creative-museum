<?php

namespace JWIED\Creativemuseum\Domain\Model\Dto;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class UserListFilterDto extends AbstractEntity
{
    /**
     * @var int
     */
    protected int $page = 1;

    /**
     * @var string
     */
    protected string $searchString = '';

    /**
     * @param int $page
     * @param string $searchString
     */
    public function __construct(int $page = 1, string $searchString = '')
    {
        $this->page = $page;
        $this->searchString = $searchString;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     * @return UserListFilterDto
     */
    public function setPage(int $page): UserListFilterDto
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return string
     */
    public function getSearchString(): string
    {
        return $this->searchString;
    }

    /**
     * @param string $searchString
     * @return UserListFilterDto
     */
    public function setSearchString(string $searchString): UserListFilterDto
    {
        $this->searchString = $searchString;
        return $this;
    }
}