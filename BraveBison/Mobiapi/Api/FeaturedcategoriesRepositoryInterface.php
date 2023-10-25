<?php

namespace BraveBison\Mobiapi\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use BraveBison\Mobiapi\Api\Data\FeaturedcategoriesInterface;


interface FeaturedcategoriesRepositoryInterface
{
    /**
     *
     * @param integer $featuredcategoriesId featuredcategoriesId
     */
    public function getById($featuredcategoriesId);

    /**
     *
     * @param integer $featuredcategoriesId featuredcategoriesId
     */
    public function deleteById($featuredcategoriesId);

    /**
     *
     * @param SearchCriteriaInterface $searchCriteria searchCriteria
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     *
     * @param FeaturedcategoriesInterface $featuredcategories featuredcategories
     */
    public function save(FeaturedcategoriesInterface $featuredcategories);

    /**
     *
     * @param FeaturedcategoriesInterface $featuredcategories featuredcategories
     */
    public function delete(FeaturedcategoriesInterface $featuredcategories);
}
