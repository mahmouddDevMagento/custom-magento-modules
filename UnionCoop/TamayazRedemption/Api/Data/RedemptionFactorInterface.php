<?php

namespace Unioncoop\TamayazRedemption\Api\Data;

interface RedemptionFactorInterface
{
    /**#@+
     * Constants for keys of data array
     */
    const ID = 'id';
    const CODE = 'code';
    const REDEMPTION_FACTOR = 'redemption_factor';
    const UPDATED_BY = 'updated_by';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get code
     *
     * @return string|null
     */
    public function getCode();

    /**
     * Set code
     *
     * @param string $code
     * @return $this
     */
    public function setCode($code);

    /**
     * Get redemption factor
     *
     * @return float|null
     */
    public function getRedemptionFactor();

    /**
     * Set redemption factor
     *
     * @param float $redemptionFactor
     * @return $this
     */
    public function setRedemptionFactor($redemptionFactor);

    /**
     * Get updated by
     *
     * @return string|null
     */
    public function getUpdatedBy();

    /**
     * Set updated by
     *
     * @param string $updatedBy
     * @return $this
     */
    public function setUpdatedBy($updatedBy);

    /**
     * Get created at
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created at
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated at
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated at
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt);
}
