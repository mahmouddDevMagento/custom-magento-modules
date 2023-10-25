<?php

namespace BraveBison\Mobiapi\Model;

use BraveBison\Mobiapi\Api\Data\BannerimageInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

class BannerimageRepository implements \BraveBison\Mobiapi\Api\BannerimageRepositoryInterface
{
    protected $_resourceModel;
    protected $_instances = [];
    protected $_collectionFactory;
    protected $_bannerimageFactory;
    protected $_instancesById = [];

    public function __construct(
        BannerimageFactory $bannerimageFactory,
        ResourceModel\Bannerimage $resourceModel,
        ResourceModel\Bannerimage\CollectionFactory $collectionFactory
    ) {
        $this->_resourceModel = $resourceModel;
        $this->_collectionFactory = $collectionFactory;
        $this->_bannerimageFactory = $bannerimageFactory;
    }

    public function save(BannerimageInterface $bannerimage)
    {
        $bannerimageId = $bannerimage->getId();
        try {
            $this->_resourceModel->save($bannerimage);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException($e->getMessage());
        }
        unset($this->_instancesById[$bannerimage->getId()]);
        return $this->getById($bannerimage->getId());
    }

    public function getById($id)
    {
        $bannerimageData = $this->_bannerimageFactory->create();
        $bannerimageData->load($id);
        $this->_instancesById[$id] = $bannerimageData;
        return $this->_instancesById[$id];
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->_collectionFactory->create();
        $collection->load();
        return $collection;
    }

    public function delete(BannerimageInterface $bannerimage)
    {
        $bannerimageId = $bannerimage->getId();
        try {
            $this->_resourceModel->delete($bannerimage);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\StateException(
                __("Unable to remove banner image with id %1", $bannerimageId)
            );
        }
        unset($this->_instancesById[$bannerimageId]);
        return true;
    }

    public function deleteById($id)
    {
        $bannerimage = $this->getById($id);
        return $this->delete($bannerimage);
    }
}
