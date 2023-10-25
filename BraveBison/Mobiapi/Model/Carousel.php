<?php
namespace BraveBison\Mobiapi\Model;

use BraveBison\Mobiapi\Api\Data\CarouselInterface;
use Magento\Framework\Model\AbstractModel;

class Carousel extends AbstractModel implements CarouselInterface
{
    const CACHE_TAG = 'mobiapi_carousel';

    protected $_cacheTag = 'mobiapi_carousel';

    protected $_eventPrefix = 'mobiapi_carousel';

    const STATUS_ENABLED = 1;

    const STATUS_DISABLED = 0;

    protected function _construct()
    {
        $this->_init(\BraveBison\Mobiapi\Model\ResourceModel\Carousel::class);
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    public function getId()
    {
        return $this->_getData(self::ID);
    }

    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    public function getTitle()
    {
        return $this->_getData(self::TITLE);
    }

    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    public function getType()
    {
        return $this->_getData(self::TYPE);
    }

    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    public function getImage()
    {
        return $this->_getData(self::IMAGE);
    }

    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }

    public function getColorCode()
    {
        return $this->_getData(self::COLOR_CODE);
    }

    public function setColorCode($colorCode)
    {
        return $this->setData(self::COLOR_CODE, $colorCode);
    }

    public function getImages()
    {
        return $this->_getData(self::IMAGES);
    }

    public function setImages($images)
    {
        return $this->setData(self::IMAGES, $images);
    }

    public function getStatus()
    {
        return $this->_getData(self::STATUS);
    }

    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    public function getSortOrder()
    {
        return $this->_getData(self::SORT_ORDER);
    }

    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    public function getImageIds()
    {
        return $this->_getData(self::IMAGE_IDS);
    }

    public function setImageIds($imageIds)
    {
        return $this->setData(self::IMAGE_IDS, $imageIds);
    }

    public function getProductIds()
    {
        return $this->_getData(self::PRODUCT_IDS);
    }

    public function setProductIds($productIds)
    {
        return $this->setData(self::PRODUCT_IDS, $productIds);
    }

    public function getStoreId()
    {
        return $this->_getData(self::STORE_ID);
    }

    public function setStoreId($storeIds)
    {
        return $this->setData(self::STORE_ID, $storeIds);
    }
}
