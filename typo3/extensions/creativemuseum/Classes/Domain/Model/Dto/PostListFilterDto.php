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
     * @var bool
     */
    protected bool $reported = false;

    /**
     * @param int $page
     * @param string $searchString
     */
    public function __construct(int $page = 1, string $searchString = '', bool $reported = false)
    {
        $this->page = $page;
        $this->searchString = $searchString;
        $this->reported = $reported;
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

    /**
     * @return bool
     */
    public function isReported(): bool
    {
        return $this->reported;
    }

    /**
     * @param bool $reported
     * @return PostListFilterDto
     */
    public function setReported(bool $reported): PostListFilterDto
    {
        $this->reported = $reported;
        return $this;
    }
}