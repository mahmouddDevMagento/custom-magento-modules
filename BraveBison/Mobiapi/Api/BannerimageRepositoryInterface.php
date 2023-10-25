<?php

namespace BraveBison\Mobiapi\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use BraveBison\Mobiapi\Api\Data\BannerimageInterface;

interface BannerimageRepositoryInterface
{
    /**
     * @param int $id
     */
    public function getById($id);

    /**
     * @param int $id
     * @return void
     */
    public function deleteById($id);

    /**
     * @param BannerimageInterface $bannerimage
     */
    public function save(BannerimageInterface $bannerimage);

    /**
     * @param BannerimageInterface $bannerimage
     */
    public function delete(BannerimageInterface $bannerimage);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
