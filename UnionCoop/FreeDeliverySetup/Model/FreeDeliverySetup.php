<?php
namespace Unioncoop\FreeDeliverySetup\Model;

use Magento\Framework\Model\AbstractModel;

class FreeDeliverySetup extends AbstractModel
{

    const CACHE_TAG = "free_delivery_setup";
    protected $_cacheTag = "free_delivery_setup";
    protected $_eventPrefix = "free_delivery_setup";

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Unioncoop\FreeDeliverySetup\Model\ResourceModel\FreeDeliverySetup::class);
    }

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->_getData('id');
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        return $this->setData('id', $id);
    }

    /**
     * Get Delivery Type
     *
     * @return string|null
     */
    public function getDeliveryType()
    {
        return $this->_getData('delivery_type');
    }

    /**
     * Set Delivery Type
     *
     * @param string $deliveryType
     * @return $this
     */
    public function setDeliveryType($deliveryType)
    {
        return $this->setData('delivery_type', $deliveryType);
    }

    /**
     * Get Day
     *
     * @return string|null
     */
    public function getDay()
    {
        return $this->_getData('day');
    }

    /**
     * Set Day
     *
     * @param string $day
     * @return $this
     */
    public function setDay($day)
    {
        return $this->setData('day', $day);
    }

    /**
     * Get Amount
     *
     * @return float|null
     */
    public function getAmount()
    {
        return $this->_getData('amount');
    }

    /**
     * Set Amount
     *
     * @param float $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        return $this->setData('amount', $amount);
    }

    /**
     * Get Creation Time
     *
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->_getData('created_at');
    }

    /**
     * Set Creation Time
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData('created_at', $createdAt);
    }

    /**
     * Get Update Time
     *
     * @return string|null
     */
    public function getUpdatedAt()
    {
        return $this->_getData('updated_at');
    }

    /**
     * Set Update Time
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData('updated_at', $updatedAt);
    }

    /**
     * Get Updated By
     *
     * @return string|null
     */
    public function getUpdatedBy()
    {
        return $this->_getData('updated_by');
    }

    /**
     * Set Updated By
     *
     * @param string $updatedBy
     * @return $this
     */
    public function setUpdatedBy($updatedBy)
    {
        return $this->setData('updated_by', $updatedBy);
    }
}
