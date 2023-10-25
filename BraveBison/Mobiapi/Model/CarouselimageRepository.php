<?php

namespace BraveBison\Mobiapi\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use BraveBison\Mobiapi\Api\Data\CarouselimageInterface;

class CarouselimageRepository implements \BraveBison\Mobiapi\Api\CarouselimageRepositoryInterface
{
    protected $_resourceModel;
    protected $_instances = [];
    protected $_collectionFactory;
    protected $_carouselimageFactory;
    protected $_instancesById = [];

    public function __construct(
        CarouselimageFactory $carouselimageFactory,
        ResourceModel\Carouselimage $resourceModel,
        ResourceModel\Carouselimage\CollectionFactory $collectionFactory
    ) {
        $this->_resourceModel = $resourceModel;
        $this->_collectionFactory = $collectionFactory;
        $this->_carouselimageFactory = $carouselimageFactory;
    }

    public function save(CarouselimageInterface $carouselimage)
    {
        $carouselimageId = $carouselimage->getId();
        try {
            $this->_resourceModel->save($carouselimage);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException($e->getMessage());
        }
        unset($this->_instancesById[$carouselimage->getId()]);
        return $this->getById($carouselimage->getId());
    }

    public function getById($id)
    {
        if (!isset($this->_instancesById[$id])) {
            $carouselimageData = $this->_carouselimageFactory->create();
            $carouselimageData->load($id);
            $this->_instancesById[$id] = $carouselimageData;
        }
        return $this->_instancesById[$id];
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->_collectionFactory->create();
        $collection->load();
        return $collection;
    }

    public function delete(CarouselimageInterface $carouselimage)
    {
        $carouselimageId = $carouselimage->getId();
        try {
            $this->_resourceModel->delete($carouselimage);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\StateException(
                __("Unable to remove carousel image with id %1", $carouselimageId)
            );
        }
        unset($this->_instancesById[$carouselimageId]);
        return true;
    }

    public function deleteById($id)
    {
        $carouselimage = $this->getById($id);
        return $this->delete($carouselimage);
    }
}
