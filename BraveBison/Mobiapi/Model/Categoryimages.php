<?php

namespace BraveBison\Mobiapi\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use BraveBison\Mobiapi\Api\Data\CategoryimagesInterface;


class Categoryimages extends AbstractModel implements CategoryimagesInterface
{
    const CACHE_TAG = "mobiapi_categoryimages";
    protected $_cacheTag = "mobiapi_categoryimages";
    protected $_eventPrefix = "mobiapi_categoryimages";
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    protected function _construct()
    {
        $this->_init(\BraveBison\Mobiapi\Model\ResourceModel\Categoryimages::class);
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

    public function getIcon()
    {
        return parent::getData(self::ICON);
    }

    public function setIcon($icon)
    {
        return $this->setData(self::ICON, $icon);
    }

    public function getBanner()
    {
        return parent::getData(self::BANNER);
    }

    public function setBanner($banner)
    {
        return $this->setData(self::BANNER, $banner);
    }

    public function getSmallbanner()
    {
        return parent::getData(self::SMALLBANNER);
    }

    public function setSmallbanner($smallbanner)
    {
        return $this->setData(self::SMALLBANNER, $smallbanner);
    }

    public function getCategoryId()
    {
        return parent::getData(self::CATEGORY_ID);
    }

    public function setCategoryId($categoryId)
    {
        return $this->setData(self::CATEGORY_ID, $categoryId);
    }

    public function getCategoryName()
    {
        return parent::getData(self::CATEGORY_NAME);
    }

    public function setCategoryName($categoryName)
    {
        return $this->setData(self::CATEGORY_NAME, $categoryName);
    }

    public function getCreatedAt()
    {
        return parent::getData(self::CREATED_AT);
    }

    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    public function getUpdatedAt()
    {
        return parent::getData(self::UPDATED_AT);
    }

    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
