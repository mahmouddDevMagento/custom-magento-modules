<?php

namespace Unioncoop\TamayazRedemption\Model\Order\Invoice\Total;

use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;
use Magento\Sales\Model\Order\Invoice;

class RedeemPoints extends AbstractTotal
{
    public function collect(Invoice $invoice)
    {
        $order = $invoice->getOrder();
        $redeemPointsAmount = $order->getRedeemPointsAmount();
//var_dump($order->getId());
//die('l');
//        if ($redeemPointsAmount && !$order->hasInvoices()) {
//            $invoice->setGrandTotal($invoice->getGrandTotal() - $redeemPointsAmount);
//            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() - $redeemPointsAmount);
////            $invoice->setRedeemPointsAmount($redeemPointsAmount);
//        }
//
        return $this;
    }

    public function fetch(Invoice $invoice)
    {
        $redeemPointsAmount = $invoice->getRedeemPointsAmount();

        if ($redeemPointsAmount) {
            return [
                'code' => 'redeem_points_amount',
                'title' => __('Redeem Points Amount'),
                'value' => -$redeemPointsAmount
            ];
        }

        return [];
    }
}
