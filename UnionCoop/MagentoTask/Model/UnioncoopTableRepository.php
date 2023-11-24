<?php

namespace UnionCoop\MagentoTask\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use UnionCoop\MagentoTask\Api\Data\UnioncoopTableInterface;
use UnionCoop\MagentoTask\Api\UnioncoopTableRepositoryInterface;
use UnionCoop\MagentoTask\Model\ResourceModel\UnioncoopTable as UnioncoopTableResourceModel;
use UnionCoop\MagentoTask\Model\ResourceModel\UnioncoopTable\CollectionFactory as UnioncoopTableCollectionFactory;

class UnioncoopTableRepository implements UnioncoopTableRepositoryInterface
{
    protected $resource;
    protected $collectionFactory;
    protected $modelFactory;

    public function __construct(
        UnioncoopTableResourceModel $resource,
        UnioncoopTableCollectionFactory $collectionFactory,
        UnioncoopTableFactory $modelFactory
    ) {
        $this->resource = $resource;
        $this->collectionFactory = $collectionFactory;
        $this->modelFactory = $modelFactory;
    }

    public function getById($id)
    {
        $model = $this->modelFactory->create();
        $this->resource->load($model, $id);
        return $model;
    }

    public function deleteById($id)
    {
        $model = $this->modelFactory->create();
        $this->resource->load($model, $id);
        if ($model->getId()) {
            $this->resource->delete($model);
            return true;
        }
        return false;
    }

    public function save(UnioncoopTableInterface $item)
    {
        try {
            $this->resource->save($item);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__($e->getMessage()));
        }
        return $item;
    }

    public function delete(UnioncoopTableInterface $item)
    {
        try {
            $this->resource->delete($item);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\StateException(__($e->getMessage()));
        }
        return true;
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
        return $collection->load();
    }
}
