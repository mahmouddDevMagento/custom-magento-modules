<?php
namespace BraveBison\Gtag\Block\Adminhtml\Creditmemo;

class Ga extends \Magento\GoogleTagManager\Block\Ga
{
    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $salesOrderCollection
     * @param \Magento\GoogleTagManager\Helper\Data $googleAnalyticsData
     * @param \Magento\Cookie\Helper\Cookie $cookieHelper
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Backend\Model\Session $backendSession
     * @param \Psr\Log\LoggerInterface $logger
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $salesOrderCollection,
        \Magento\GoogleTagManager\Helper\Data $googleAnalyticsData,
        \Magento\Cookie\Helper\Cookie $cookieHelper,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Backend\Model\Session $backendSession,
        \Psr\Log\LoggerInterface $logger,
        array $data = []
    ) {
        $this->backendSession = $backendSession;
        $this->logger = $logger;
        parent::__construct(
            $context,
            $salesOrderCollection,
            $googleAnalyticsData,
            $cookieHelper,
            $jsonHelper,
            $data
        );
    }

    /**
     * Get order ID for the recently created creditmemo
     *
     * @return string
     */
    public function getOrderId()
    {
        $orderId = $this->backendSession->getData('bb_googleanalytics_creditmemo_order', true);

        if ($orderId) {
            $this->logger->info('that is get orderId return value',(array)$orderId);

            return $orderId;
        }
        return '';
    }

    /**
     * Build json for dataLayer.push action
     *
     * @return string|null
     */
    public function getRefundJson()
    {

        $orderId = $this->getOrderId();
//        $this->logger->info('that is credit memo for order before',(array)$orderId);

        if (!$orderId) {
            return null;
        }
        $refundJson = new \StdClass();
//        $refundJson->event = 'refund';
        $refundJson->ecommerce = new \StdClass();
        $refundJson->ecommerce->refund = new \StdClass();
        $refundJson->ecommerce->refund->actionField  = new \StdClass();
        $refundJson->ecommerce->refund->actionField->id = $orderId;

        return json_encode($refundJson);
    }
}
