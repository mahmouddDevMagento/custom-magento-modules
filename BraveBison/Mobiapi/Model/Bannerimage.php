<?php

namespace BraveBison\Mobiapi\Model;

use Magento\Framework\Model\AbstractModel;
use BraveBison\Mobiapi\Api\Data\BannerimageInterface;

class Bannerimage extends AbstractModel implements BannerimageInterface
{
    const CACHE_TAG = "mobiapi_bannerimage";
    protected $_cacheTag = "mobiapi_bannerimage";
    protected $_eventPrefix = "mobiapi_bannerimage";
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    
    protected function _construct()
    {
        $this->_init(\BraveBison\Mobiapi\Model\ResourceModel\Bannerimage::class);
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }


    public function getId()
    {
        return parent::getData(self::ID);
    }

    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    public function getType()
    {
        return parent::getData(self::TYPE);
    }

    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    public function getStatus()
    {
        return parent::getData(self::STATUS);
    }

    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    public function getStoreId()
    {
        return parent::getData(self::STORE_ID);
    }

    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    public function getImage()
    {
        return parent::getData(self::IMAGE);
    }

    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }

    public function getProductCatId()
    {
        return parent::getData(self::PRODUCT_CAT_ID);
    }

    public function setProductCatId($productCatId)
    {
        return $this->setData(self::PRODUCT_CAT_ID, $productCatId);
    }

    public function getSortOrder()
    {
        return parent::getData(self::SORT_ORDER);
    }

    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    public function getUpdateTime()
    {
        return parent::getData(self::UPDATE_TIME);
    }

    public function setUpdateTime($updatedAt)
    {
        return $this->setData(self::UPDATE_TIME, $updatedAt);
    }

    public function getCreatedTime()
    {
        return parent::getData(self::CREATED_TIME);
    }

    public function setCreatedTime($createdAt)
    {
        return $this->setData(self::CREATED_TIME, $createdAt);
    }
}