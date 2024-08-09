<?php

namespace Unioncoop\TamayazRedemption\Model\Total\Quote;

use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Quote\Model\Quote;

class RedeemPoints extends AbstractTotal
{

    public function collect(
        Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        parent::collect($quote, $shippingAssignment, $total);

        $redeemPointsAmount = $quote->getRedeemPointsAmount();

        if ($redeemPointsAmount > 0 ) {

            // Calculate the new grand total
            $newGrandTotal = $total->getGrandTotal() - $redeemPointsAmount;
            $newBaseGrandTotal = $total->getBaseGrandTotal() - $redeemPointsAmount;

            // Check if the new grand total is not already subtracted
            if ($newGrandTotal < $total->getGrandTotal()) {
                $total->setGrandTotal($newGrandTotal);
                $total->setBaseGrandTotal($newBaseGrandTotal);
                $quote->setUseRedeemPointsAmount(true); // Set the flag to indicate redemption is applied
            }
        }

        return $this;
    }

    public function fetch(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        return [
            'code' => $this->getCode(),
            'title' => __('Redeem Points'),
            'value' => $quote->getRedeemPointsAmount()
        ];
    }

}
