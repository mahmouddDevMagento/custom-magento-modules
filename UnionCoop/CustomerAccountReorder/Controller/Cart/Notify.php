<?php

namespace Unioncoop\CustomerAccountReorder\Controller\Cart;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Data\Form\FormKey;
use Magento\Catalog\Model\ProductFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Unioncoop\OutOfStockCartPopup\Model\StockNotificationFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Notify extends Action
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var FormKey
     */
    protected $formKey;

    /**
     * @var ProductFactory
     */
    protected $productFactory;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var StockNotificationFactory
     */
    protected $stockNotificationFactory;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    public function __construct(
        Context $context,
        FormKey $formKey,
        ProductFactory $productFactory,
        CustomerSession $customerSession,
        StockNotificationFactory $stockNotificationFactory,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->formKey = $formKey;
        $this->productFactory = $productFactory;
        $this->customerSession = $customerSession;
        $this->stockNotificationFactory = $stockNotificationFactory;
        $this->jsonFactory = $jsonFactory;
        $this->scopeConfig = $scopeConfig;

    }

    public function execute()
    {
        $notifyMessage = $this->scopeConfig->getValue(
            'grocery/reorder_popup_notify_message/default_message',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $resultJson = $this->jsonFactory->create();

        $productId = $this->getRequest()->getParam('product_id');

        if (!$productId) {
            return $resultJson->setData(['success' => false, 'error' => 'Product ID is missing']);
        }

        $product = $this->productFactory->create()->load($productId);

        if (!$product->getId()) {
            return $resultJson->setData(['success' => false, 'error' => 'Invalid product ID']);
        }

        $customerId = $this->customerSession->getCustomerId();
        $productSku = $product->getSku();

        // For one customer only one entry will be available for one product with notification_status = 0
        $existingNotification = $this->stockNotificationFactory->create()->getCollection()
            ->addFieldToFilter('sku', $productSku)
            ->addFieldToFilter('customer_id', $customerId)
            ->addFieldToFilter('notification_status', 0)
            ->getFirstItem();


        if ($existingNotification->getId()) {
            return $resultJson->setData(['success' => true, 'message' => $notifyMessage]);
        }

        // If entry does not exist, save the notification
        $productName = $product->getName();
        $customerName = $this->customerSession->getCustomer()->getName();
        $customerEmail = $this->customerSession->getCustomer()->getEmail();

        $success = $this->_saveNotificationData($productName, $productSku, $customerId, $customerName, $customerEmail);

        return $resultJson->setData(['success' => $success, 'message' => $notifyMessage]);
    }


    protected function _saveNotificationData($productName, $productSku, $customerId, $customerName, $customerEmail)
    {
        try {
            $stockNotification = $this->stockNotificationFactory->create();

            $stockNotification->setName($productName)
                ->setSku($productSku)
                ->setCustomerId($customerId)
                ->setCustomerName($customerName)
                ->setCustomerEmail($customerEmail)
                ->setNotificationStatus(0);

            $stockNotification->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
