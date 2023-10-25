<?php

namespace BraveBison\Mobiapi\Model;

use Magento\Framework\Model\AbstractModel;
use BraveBison\Mobiapi\Api\Data\WalkthroughInterface;
use Magento\Framework\DataObject\IdentityInterface;

class Walkthrough extends AbstractModel implements WalkthroughInterface, IdentityInterface
{

    const CACHE_TAG = "mobiapi_walkthrough";
    protected $_cacheTag = "mobiapi_walkthrough";
    protected $_eventPrefix = "mobiapi_walkthrough";
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    protected function _construct()
    {
        $this->_init(\BraveBison\Mobiapi\Model\ResourceModel\Walkthrough::class);
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'),
                self::STATUS_DISABLED => __('Disabled')];
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . "_" . $this->getId()];
    }

    public function getId()
    {
        return parent::getData(self::ID);
    }

    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    public function getTitle()
    {
        return parent::getData(self::TITLE);
    }

    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    public function getDescription()
    {
        return parent::getData(self::DESCRIPTION);
    }

    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    public function getColorCode()
    {
        return parent::getData(self::COLOR_CODE);
    }

    public function setColorCode($colorCode)
    {
        return $this->setData(self::COLOR_CODE, $colorCode);
    }

    public function getImage()
    {
        return parent::getData(self::IMAGE);
    }

    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }

    public function getStatus()
    {
        return parent::getData(self::STATUS);
    }
    
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    public function getSortOrder()
    {
        return parent::getData(self::SORT_ORDER);
    }

    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }
}
