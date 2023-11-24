<?php

namespace UnionCoop\MagentoTask\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use UnionCoop\MagentoTask\Api\Data\UnioncoopTableInterface;

interface UnioncoopTableRepositoryInterface
{
    /**
     * Get item by ID
     *
     * @param int $id Item ID
     * @return UnioncoopTableInterface
     */
    public function getById($id);

    /**
     * Delete item by ID
     *
     * @param int $id Item ID
     * @return bool
     */
    public function deleteById($id);

    /**
     * Save item
     *
     * @param UnioncoopTableInterface $item Item to save
     * @return UnioncoopTableInterface
     */
    public function save(UnioncoopTableInterface $item);

    /**
     * Delete item
     *
     * @param UnioncoopTableInterface $item Item to delete
     * @return bool
     */
    public function delete(UnioncoopTableInterface $item);

    /**
     * Get a list of items based on search criteria
     *
     * @param SearchCriteriaInterface $searchCriteria Search criteria
     * @return \UnionCoop\MagentoTask\Api\Data\UnioncoopTableSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
