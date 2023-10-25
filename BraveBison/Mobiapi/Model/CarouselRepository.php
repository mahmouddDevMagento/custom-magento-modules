<?php

namespace BraveBison\Mobiapi\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use BraveBison\Mobiapi\Api\Data\CarouselInterface;

class CarouselRepository implements \BraveBison\Mobiapi\Api\CarouselRepositoryInterface
{
    protected $resourceModel;
    protected $instances = [];
    protected $collectionFactory;
    protected $carouselFactory;
    protected $instancesById = [];

    public function __construct(
        CarouselFactory $carouselFactory,
        ResourceModel\Carousel $resourceModel,
        ResourceModel\Carousel\CollectionFactory $collectionFactory
    ) {
        $this->resourceModel = $resourceModel;
        $this->collectionFactory = $collectionFactory;
        $this->carouselFactory = $carouselFactory;
    }

    public function save(CarouselInterface $carousel)
    {
        $carouselId = $carousel->getId();
        try {
            $this->resourceModel->save($carousel);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException($e->getMessage());
        }
        unset($this->instancesById[$carousel->getId()]);
        return $this->getById($carousel->getId());
    }

    public function getById($id)
    {
        if (!isset($this->instancesById[$id])) {
            $carouselData = $this->carouselFactory->create();
            $carouselData->load($id);
            $this->instancesById[$id] = $carouselData;
        }
        return $this->instancesById[$id];
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
        $collection->load();
        return $collection;
    }

    public function delete(CarouselInterface $carousel)
    {
        $carouselId = $carousel->getId();
        try {
            $this->resourceModel->delete($carousel);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\StateException(
                __("Unable to remove carousel with ID %1", $carouselId)
            );
        }
        unset($this->instancesById[$carouselId]);
        return true;
    }

    public function deleteById($id)
    {
        $carousel = $this->getById($id);
        return $this->delete($carousel);
    }
}
