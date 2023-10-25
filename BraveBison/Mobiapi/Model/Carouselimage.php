<?php

namespace BraveBison\Mobiapi\Model;

use Magento\Framework\Model\AbstractModel;
use BraveBison\Mobiapi\Api\Data\CarouselimageInterface;

class Carouselimage extends AbstractModel implements CarouselimageInterface
{
    const CACHE_TAG = "mobiapi_carouselimage";
    protected $_cacheTag = "mobiapi_carouselimage";
    protected $_eventPrefix = "mobiapi_carouselimage";
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    protected function _construct()
    {
        $this->_init(\BraveBison\Mobiapi\Model\ResourceModel\Carouselimage::class);
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

    public function getImage()
    {
        return parent::getData(self::IMAGE);
    }

    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }

    public function getType()
    {
        return parent::getData(self::TYPE);
    }

    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    public function getTitle()
    {
        return parent::getData(self::TITLE);
    }

    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    public function getProductCatId()
    {
        return parent::getData(self::PRODUCT_CAT_ID);
    }

    public function setProductCatId($productCatId)
    {
        return $this->setData(self::PRODUCT_CAT_ID, $productCatId);
    }

    public function getStatus()
    {
        return parent::getData(self::STATUS);
    }

    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }
}
