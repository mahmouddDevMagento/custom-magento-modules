<?php

namespace Tofaha\RefundWallet\Observer;

use Magento\Framework\Event\ObserverInterface;
use Webkul\Walletsystem\Model\Wallettransaction;
use Webkul\Walletsystem\Model\WalletUpdateData;
use Webkul\Walletsystem\Helper\Data;

class RefundWalletAfterCancel implements ObserverInterface
{
    /**
     * @var WalletUpdateData
     */
    protected $walletUpdate;

    /**
     * @var Data
     */
    protected $walletHelper;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param WalletUpdateData $walletUpdate
     * @param Data $walletHelper*
     */
    public function __construct(
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        WalletUpdateData $walletUpdate,
        Data $walletHelper
       ) {
        $this->orderRepository = $orderRepository;
        $this->walletUpdate = $walletUpdate;
        $this->walletHelper = $walletHelper;

    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $customerId = $order->getCustomerId();
        $orderIncrementId = $order->getIncrementId();
        $orderId = $order->getId();

        if ($order->isCanceled() || $order->getState() == \Magento\Sales\Model\Order::STATE_CANCELED)
        {
            try {
                $orderWalletAmount = $order->getData('wallet_amount');

                if ($orderWalletAmount < 0)
                {
                    $amount = abs($orderWalletAmount);
                    $AmountData = [
                        'customerid' => $customerId,
                        'walletamount' => $amount,
                        'walletactiontype' => Wallettransaction::WALLET_ACTION_TYPE_CREDIT,
                        'curr_code' => $this->walletHelper->getCurrentCurrencyCode(),
                        'curr_amount' => $amount,
                        'walletnote' => 'Remaining of order No: ' . $orderIncrementId,
                        'sender_id' => 0,
                        'sender_type' => Wallettransaction::ADMIN_TRANSFER_TYPE,
                        'order_id' => $orderId,
                        'status' => Wallettransaction::WALLET_TRANS_STATE_APPROVE,
                        'increment_id' => ''
                    ];

                    $this->walletUpdate->creditAmount($customerId, $AmountData);

                }

            } catch (\Exception $e){
            }

        } else {
            return;
        }
    }
}