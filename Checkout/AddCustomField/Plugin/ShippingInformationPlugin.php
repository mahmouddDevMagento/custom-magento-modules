<?php

namespace Checkout\AddCustomField\Plugin;

use Magento\Quote\Api\CartRepositoryInterface;

class ShippingInformationPlugin
{
    /**
     * @var CartRepositoryInterface
     */
    public $cartRepository;

    /**
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(
        CartRepositoryInterface $cartRepository
    ) {
        $this->cartRepository = $cartRepository;
    }

    public function afterSaveAddressInformation(
        \Magento\Checkout\Api\ShippingInformationManagementInterface $subject,
        \Magento\Checkout\Api\Data\PaymentDetailsInterface $result,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation)
    {
        $quote = $this->cartRepository->getActive($cartId);
        $extensionAttr = $addressInformation->getShippingAddress()->getExtensionAttributes();
        $deliveryNote =  $extensionAttr->getDeliveryNote();
        $quote->setDeliveryNote($deliveryNote);
        $this->cartRepository->save($quote);
        return $result;
    }
}
