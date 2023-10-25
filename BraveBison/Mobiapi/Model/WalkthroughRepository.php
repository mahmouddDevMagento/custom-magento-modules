<?php

namespace BraveBison\Mobiapi\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use BraveBison\Mobiapi\Api\Data\WalkthroughInterface;

class WalkthroughRepository implements \BraveBison\Mobiapi\Api\WalkthroughRepositoryInterface
{

    protected $_resourceModel;
    protected $_instances = [];
    protected $_collectionFactory;
    protected $_instancesById = [];

    public function __construct(
        WalkthroughFactory $walkthroughFactory,
        ResourceModel\Walkthrough $resourceModel,
        ResourceModel\Walkthrough\CollectionFactory $collectionFactory
    ) {
        $this->_resourceModel = $resourceModel;
        $this->_collectionFactory = $collectionFactory;
        $this->_walkthroughFactory = $walkthroughFactory;
    }


    public function save(WalkthroughInterface $walkthrough)
    {
        $walkThroughId = $walkthrough->getId();
        try {
            $this->_resourceModel->save($walkthrough);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException($e->getMessage());
        }
        unset($this->_instancesById[$walkthrough->getId()]);
        return $this->getById($walkthrough->getId());
    }

    public function getById($id)
    {
        $walkthroughData = $this->_walkthroughFactory->create();
        $walkthroughData->load($id);
        $this->_instancesById[$id] = $walkthroughData;
        return $this->_instancesById[$id];
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->_collectionFactory->create();
        $collection->load();
        return $collection;
    }

    public function delete(WalkthroughInterface $walkthrough)
    {
        $walkThroughId = $walkthrough->getId();
        try {
            $this->_resourceModel->delete($walkthrough);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\StateException(
                __("Unable to remove banner image with id %1", $walkThroughId)
            );
        }
        unset($this->_instancesById[$walkThroughId]);
        return true;
    }

    public function deleteById($id)
    {
        $walkThrough = $this->getById($id);
        return $this->delete($walkThrough);
    }
}
