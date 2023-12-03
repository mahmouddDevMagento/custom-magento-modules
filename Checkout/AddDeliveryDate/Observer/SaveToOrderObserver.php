<?php
namespace Checkout\AddDeliveryDate\Observer;

class SaveToOrderObserver implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $event = $observer->getEvent();
        $quote = $event->getQuote();
        $order = $event->getOrder();
        $order->setData('delivery_date', $quote->getData('delivery_date'));
    }
}
