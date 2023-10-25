<?php

namespace BraveBison\Mobiapi\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use BraveBison\Mobiapi\Api\Data\CategoryimagesInterface;

class CategoryimagesRepository implements \BraveBison\Mobiapi\Api\CategoryimagesRepositoryInterface
{
    protected $_resourceModel;
    protected $_instances = [];
    protected $_collectionFactory;
    protected $_instancesById = [];
    protected $_categoryimagesFactory;

    public function __construct(
        CategoryimagesFactory $categoryimagesFactory,
        ResourceModel\Categoryimages $resourceModel,
        ResourceModel\Categoryimages\CollectionFactory $collectionFactory
    ) {
        $this->_resourceModel = $resourceModel;
        $this->_collectionFactory = $collectionFactory;
        $this->_categoryimagesFactory = $categoryimagesFactory;
    }

    public function save(CategoryimagesInterface $categoryimages)
    {
        $categoryimagesId = $categoryimages->getId();
        try {
            $this->_resourceModel->save($categoryimages);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException($e->getMessage());
        }
        unset($this->_instancesById[$categoryimages->getId()]);
        return $this->getById($categoryimages->getId());
    }

    public function getById($id)
    {
        $categoryimagesData = $this->_categoryimagesFactory->create();
        $categoryimagesData->load($id);
        $this->_instancesById[$id] = $categoryimagesData;
        return $this->_instancesById[$id];
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->_collectionFactory->create();
        $collection->load();
        return $collection;
    }

    public function delete(CategoryimagesInterface $categoryimages)
    {
        $categoryimagesId = $categoryimages->getId();
        try {
            $this->_resourceModel->delete($categoryimages);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\StateException(
                __("Unable to remove categoryimages record with id %1", $categoryimagesId)
            );
        }
        unset($this->_instancesById[$categoryimagesId]);
        return true;
    }

    public function deleteById($id)
    {
        $categoryimages = $this->getById($id);
        return $this->delete($categoryimages);
    }
}
