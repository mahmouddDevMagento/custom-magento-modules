<?php

namespace BraveBison\Mobiapi\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use BraveBison\Mobiapi\Api\Data\CarouselimageInterface;

interface CarouselimageRepositoryInterface
{
    /**
     * @param int $id
     * @return \BraveBison\Mobiapi\Api\Data\CarouselimageInterface
     */
    public function getById($id);

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById($id);

    /**
     * @param \BraveBison\Mobiapi\Api\Data\CarouselimageInterface $carouselimage
     * @return \BraveBison\Mobiapi\Api\Data\CarouselimageInterface
     */
    public function save(CarouselimageInterface $carouselimage);

    /**
     * @param \BraveBison\Mobiapi\Api\Data\CarouselimageInterface $carouselimage
     * @return bool
     */
    public function delete(CarouselimageInterface $carouselimage);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \BraveBison\Mobiapi\Api\Data\CarouselimageInterface[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
