<?php

namespace Unioncoop\TamayazRedemption\Model\Order\Creditmemo\Total;

use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal as CreditmemoAbstractTotal;
use Magento\Sales\Model\Order\Creditmemo;

class RedeemPoints extends CreditmemoAbstractTotal
{
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Creditmemo\CollectionFactory
     */
    protected $creditMemoCollectionFactory;

    /**
     * @param \Magento\Sales\Model\ResourceModel\Order\Creditmemo\CollectionFactory $creditMemoCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\Creditmemo\CollectionFactory $creditMemoCollectionFactory,
        array $data = []
    ){
        parent::__construct($data);
        $this->creditMemoCollectionFactory = $creditMemoCollectionFactory;
    }

    /**
     * Check if it has redeem points to refund
     *
     * @param \Magento\Sales\Model\Order $order
     * @return bool
     */
    public function hasRedeemPointsToRefund(\Magento\Sales\Model\Order $order)
    {
        if ($order->getRedeemPointsAmount()) {
            $collection = $this->creditMemoCollectionFactory->create()
                ->addFieldToFilter('order_id', $order->getId());
            if ($collection->count()) {
                return false;
            } else {
                return true;
            }
        }
        return false;
    }

    public function collect(Creditmemo $creditmemo)
    {
        try {
            $order = $creditmemo->getOrder();
            $invoice = $creditmemo->getInvoice();
            $redeemPointsAmount = $creditmemo->getRedeemPointsAmount();


            $originalRedeemPointsAmount = $order->getRedeemPointsAmount();
            if ($invoice){
                $originalRedeemPointsAmount = $invoice->getRedeemPointsAmount();

            }
            $grandTotal = $creditmemo->getGrandTotal();

            // Validate redemption amount
            if ($redeemPointsAmount > $originalRedeemPointsAmount) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Redemption amount cannot be greater than the invoice redemption amount.')
                );
            }

            if ($redeemPointsAmount > $grandTotal) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Redemption amount cannot be greater than the grand total.')
                );
            }

            // Adjust credit memo totals
            $creditmemo->setGrandTotal($grandTotal - $redeemPointsAmount);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() - $redeemPointsAmount);
            $creditmemo->setRedeemPointsAmount($redeemPointsAmount);

        } catch (\Exception $e) {
            // Log the exception or handle it as needed
            throw new \Magento\Framework\Exception\LocalizedException(
                __('An error occurred while processing redeem points: %1', $e->getMessage())
            );
        }

        return $this;
    }

    public function fetch(Creditmemo $creditmemo)
    {
        $redeemPointsAmount = $creditmemo->getRedeemPointsAmount();

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
