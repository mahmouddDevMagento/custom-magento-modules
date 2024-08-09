<?php

namespace Unioncoop\TamayazRedemption\Model;

use Unioncoop\TamayazRedemption\Api\Data\RedemptionFactorInterface;
use Magento\Framework\Model\AbstractModel;

class RedemptionFactor extends AbstractModel implements RedemptionFactorInterface
{
    protected function _construct()
    {
        $this->_init(\Unioncoop\TamayazRedemption\Model\ResourceModel\RedemptionFactor::class);
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @inheritDoc
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @inheritDoc
     */
    public function getCode()
    {
        return $this->getData(self::CODE);
    }

    /**
     * @inheritDoc
     */
    public function setCode($code)
    {
        return $this->setData(self::CODE, $code);
    }

    /**
     * @inheritDoc
     */
    public function getRedemptionFactor()
    {
        return $this->getData(self::REDEMPTION_FACTOR);
    }

    /**
     * @inheritDoc
     */
    public function setRedemptionFactor($redemptionFactor)
    {
        return $this->setData(self::REDEMPTION_FACTOR, $redemptionFactor);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedBy()
    {
        return $this->getData(self::UPDATED_BY);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedBy($updatedBy)
    {
        return $this->setData(self::UPDATED_BY, $updatedBy);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
