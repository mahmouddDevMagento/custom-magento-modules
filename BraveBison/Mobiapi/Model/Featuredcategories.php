<?php

namespace BraveBison\Mobiapi\Model;

use Magento\Framework\Model\AbstractModel;
use BraveBison\Mobiapi\Api\Data\FeaturedcategoriesInterface;

class Featuredcategories extends AbstractModel implements FeaturedcategoriesInterface
{
    const CACHE_TAG = "mobiapi_featuredcategories";
    protected $_cacheTag = "mobiapi_featuredcategories";
    protected $_eventPrefix = "mobiapi_featuredcategories";
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    protected function _construct()
    {
        $this->_init(\BraveBison\Mobiapi\Model\ResourceModel\Featuredcategories::class);
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

    public function getStatus()
    {
        return parent::getData(self::STATUS);
    }

    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    public function getImage()
    {
        return parent::getData(self::IMAGE);
    }

    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }

    public function getStoreId()
    {
        return parent::getData(self::STORE_ID);
    }

    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    public function getSortOrder()
    {
        return parent::getData(self::SORT_ORDER);
    }

    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    public function getCategoryId()
    {
        return parent::getData(self::CATEGORY_ID);
    }

    public function setCategoryId($categoryId)
    {
        return $this->setData(self::CATEGORY_ID, $categoryId);
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