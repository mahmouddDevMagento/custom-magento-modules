<?php

namespace Unioncoop\TamayazRedemption\Block\Sales\GroceryOrder;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\DataObject;

class RedeemPoints extends \Magento\Framework\View\Element\Template
{
    public function __construct(Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

    /**
     * Get label cell tag properties
     *
     * @return string
     */
    public function getLabelProperties()
    {
        return $this->getParentBlock()->getLabelProperties();
    }

    /**
     * Get order store object
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }

    /**
     * Get totals source object
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    /**
     * Get value cell tag properties
     *
     * @return string
     */
    public function getValueProperties()
    {
        return $this->getParentBlock()->getValueProperties();
    }

    public function initTotals()
    {
        $source = $this->getParentBlock()->getSource();
        $redeemPointsAmount = $source->getRedeemPointsAmount();

//        if ($redeemPointsAmount && $redeemPointsAmount > 0) {
            $redeemPointsLabel = __('Redeem Points Amount');
            $total = new \Magento\Framework\DataObject(
                [
                    'code' => 'redeem_points_amount',
                    'strong' => false,
                    'label' => $redeemPointsLabel,
                    'value' => $redeemPointsAmount,
                    'base_value' => $redeemPointsAmount,
                ]
            );
            $this->getParentBlock()->addTotal($total, 'redeem_points_amount');
//        }
        return $this;
    }
}
