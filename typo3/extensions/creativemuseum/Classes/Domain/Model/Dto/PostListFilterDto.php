<?php

namespace JWIED\Creativemuseum\Domain\Model\Dto;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class PostListFilterDto extends AbstractEntity
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
     * @return PostListFilterDto
     */
    public function setPage(int $page): PostListFilterDto
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
     * @return PostListFilterDto
     */
    public function setSearchString(string $searchString): PostListFilterDto
    {
        $this->searchString = $searchString;
        return $this;
    }
}