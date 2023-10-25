<?php

namespace BraveBison\Mobiapi\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use BraveBison\Mobiapi\Api\Data\CarouselInterface;

interface CarouselRepositoryInterface
{
    /**
     * @param int $id
     * @return \BraveBison\Mobiapi\Api\Data\CarouselInterface
     */
    public function getById($id);

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById($id);

    /**
     * @param \BraveBison\Mobiapi\Api\Data\CarouselInterface $carousel
     * @return \BraveBison\Mobiapi\Api\Data\CarouselInterface
     */
    public function save(CarouselInterface $carousel);

    /**
     * @param \BraveBison\Mobiapi\Api\Data\CarouselInterface $carousel
     * @return bool
     */
    public function delete(CarouselInterface $carousel);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \BraveBison\Mobiapi\Api\Data\CarouselInterface[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
