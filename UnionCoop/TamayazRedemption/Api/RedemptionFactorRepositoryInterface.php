<?php

namespace Unioncoop\TamayazRedemption\Api;

use Unioncoop\TamayazRedemption\Api\Data\RedemptionFactorInterface;

interface RedemptionFactorRepositoryInterface
{
    /**
     * Save redemption factor.
     *
     * @param RedemptionFactorInterface $redemptionFactor
     * @return RedemptionFactorInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(RedemptionFactorInterface $redemptionFactor);

    /**
     * Retrieve redemption factor.
     *
     * @param int $redemptionFactorId
     * @return RedemptionFactorInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($redemptionFactorId);

    /**
     * Delete redemption factor.
     *
     * @param RedemptionFactorInterface $redemptionFactor
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(RedemptionFactorInterface $redemptionFactor);

    /**
     * Delete redemption factor by ID.
     *
     * @param int $redemptionFactorId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($redemptionFactorId);
}
