<?php

namespace BraveBison\Mobiapi\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use BraveBison\Mobiapi\Api\Data\FeaturedcategoriesInterface;

class FeaturedcategoriesRepository implements \BraveBison\Mobiapi\Api\FeaturedcategoriesRepositoryInterface
{
    protected $_resourceModel;
    protected $_instances = [];
    protected $_collectionFactory;
    protected $_bannerimageFactory;
    protected $_instancesById = [];

    public function __construct(
        ResourceModel\Featuredcategories $resourceModel,
        FeaturedcategoriesFactory $featuredcategoriesFactory,
        ResourceModel\Featuredcategories\CollectionFactory $collectionFactory
    ) {
        $this->_resourceModel = $resourceModel;
        $this->_collectionFactory = $collectionFactory;
        $this->_featuredcategoriesFactory = $featuredcategoriesFactory;
    }

    public function save(FeaturedcategoriesInterface $featuredcategories)
    {
        $featuredcategoriesId = $featuredcategories->getId();
        try {
            $this->_resourceModel->save($featuredcategories);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException($e->getMessage());
        }
        unset($this->_instancesById[$featuredcategories->getId()]);
        return $this->getById($featuredcategories->getId());
    }


    public function getById($id)
    {
        $featuredcategoriesData = $this->_featuredcategoriesFactory->create();
        $featuredcategoriesData->load($id);
        $this->_instancesById[$id] = $featuredcategoriesData;
        return $this->_instancesById[$id];
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->_collectionFactory->create();
        $collection->load();
        return $collection;
    }

    public function delete(FeaturedcategoriesInterface $featuredcategories)
    {
        $featuredcategoriesId = $featuredcategories->getId();
        try {
            $this->_resourceModel->delete($featuredcategories);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\StateException(
                __("Unable to remove featuredcategories record with id %1", $featuredcategoriesId)
            );
        }
        unset($this->_instancesById[$featuredcategoriesId]);
        return true;
    }

    public function deleteById($id)
    {
        $featuredcategories = $this->getById($id);
        return $this->delete($featuredcategories);
    }
}
