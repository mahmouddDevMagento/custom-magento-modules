<?php

namespace BraveBison\Mobiapi\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use BraveBison\Mobiapi\Api\Data\CategoryimagesInterface;


interface CategoryimagesRepositoryInterface
{
    /**
     *
     * @param integer $categoryimagesId
     */
    public function getById($categoryimagesId);

    /**
     *
     * @param integer $categoryimagesId
     *
     * @return null
     */
    public function deleteById($categoryimagesId);

    /**
     *
     * @param CategoryimagesInterface $categoryimages
     */
    public function save(CategoryimagesInterface $categoryimages);

    /**
     *
     * @param CategoryimagesInterface $categoryimages
     *
     * @return null
     */
    public function delete(CategoryimagesInterface $categoryimages);

    /**
     *
     * @param SearchCriteriaInterface $searchCriteria searchCriteria
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
