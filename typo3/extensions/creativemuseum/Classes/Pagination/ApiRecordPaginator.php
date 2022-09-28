<?php

namespace JWIED\Creativemuseum\Pagination;

use TYPO3\CMS\Core\Pagination\AbstractPaginator;

class ApiRecordPaginator extends AbstractPaginator
{
    protected array $collection;

    protected int $itemsTotal;

    public function __construct(
        array $collection,
        int $currentPageNumber = 1,
        int $itemsPerPage = 10,
        int $itemsTotal = 999
    ) {
        $this->collection = $collection;
        $this->itemsTotal = $itemsTotal;
        $this->setCurrentPageNumber($currentPageNumber);
        $this->setItemsPerPage($itemsPerPage);

        $this->updateInternalState();
    }

    protected function updatePaginatedItems(int $itemsPerPage, int $offset): void
    {
        // TODO: Implement updatePaginatedItems() method.
    }

    protected function getTotalAmountOfItems(): int
    {
        return $this->itemsTotal;
    }

    protected function getAmountOfItemsOnCurrentPage(): int
    {
        return count($this->collection);
    }

    public function getPaginatedItems(): iterable
    {
        return $this->collection;
    }
}