<?php
namespace BraveBison\Gtag\Plugin;

use Magento\Sales\Api\OrderRepositoryInterface;

class Creditmemo
{
    /**
     * @var \Magento\GoogleTagManager\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @param \Magento\GoogleTagManager\Helper\Data $helper
     * @param \Magento\Backend\Model\Session $backendSession
     */
    public function __construct(
        \Magento\GoogleTagManager\Helper\Data $helper,
        \Magento\Backend\Model\Session $backendSession,
        \Psr\Log\LoggerInterface $logger,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->helper = $helper;
        $this->backendSession = $backendSession;
        $this->logger = $logger;
        $this->orderRepository = $orderRepository;

    }

    public function afterSave(
        \Magento\Sales\Api\CreditmemoRepositoryInterface $subject, $result, $creditmemo
    ) {

        $orderId = $creditmemo->getOrderId();
        $order = $this->orderRepository->get($orderId);
        $this->logger->info('that is plugin order data ',(array)$order->getData());
        $orderId = $creditmemo->getOrderId();
        $this->backendSession->setData('bb_googleanalytics_creditmemo_order', $orderId);
        return $result;
    }
}
