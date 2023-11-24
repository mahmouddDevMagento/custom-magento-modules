<?php

namespace UnionCoop\MagentoTask\Model;

use Magento\Framework\Model\AbstractModel;
use UnionCoop\MagentoTask\Api\Data\UnioncoopTableInterface;

class UnioncoopTable extends AbstractModel implements UnioncoopTableInterface
{
    const CACHE_TAG = "unioncoop_table";
    protected $_cacheTag = "unioncoop_table";
    protected $_eventPrefix = "unioncoop_table";

    protected function _construct()
    {
        $this->_init(\UnionCoop\MagentoTask\Model\ResourceModel\UnioncoopTable::class);
    }

    public function getId()
    {
        return $this->getData(self::ID);
    }

    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    public function getProductSku()
    {
        return $this->getData(self::PRODUCT_SKU);
    }

    public function setProductSku($productSku)
    {
        return $this->setData(self::PRODUCT_SKU, $productSku);
    }

    public function getProductName()
    {
        return $this->getData(self::PRODUCT_NAME);
    }

    public function setProductName($productName)
    {
        return $this->setData(self::PRODUCT_NAME, $productName);
    }

    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
}


