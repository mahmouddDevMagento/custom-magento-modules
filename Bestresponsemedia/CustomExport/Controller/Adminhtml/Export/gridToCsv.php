<?php

namespace BestResponseMedia\CustomExport\Controller\Adminhtml\Export;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Model\ProductFactory;
use Magento\Checkout\Helper\Data;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Directory\Model\Country;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Psr\Log\LoggerInterface;
use Magento\Sales\Model\Order\Creditmemo\Item as CreditMemeotems;

class GridToCsv extends Action
{

    /**
     * @var Country
     */
    protected $country;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var CollectionFactory
     */
    protected $_orderCollectionFactory;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $CustomerRepositoryInterface;

    protected $_productFactory;
    /**
     * @var Data
     */
    protected $_checkoutHelper;

    protected $creditMemo;

    /**
     * Get grid csv file
     *
     * @param Context $context
     * @param CollectionFactory $orderCollectionFactory
     * @param Order $order
     * @param CustomerRepositoryInterface $customerRepositoryInterface
     * @param Country $country
     * @param LoggerInterface $logger
     * @param ProductFactory $productFactory
     * @param Data $checkoutHelper
     */
    public function __construct(
        Context                                                    $context,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Model\Order                                 $order,
        \Magento\Customer\Api\CustomerRepositoryInterface          $customerRepositoryInterface,
        Country                                                    $country,
        LoggerInterface                                            $logger,
        \Magento\Catalog\Model\ProductFactory                      $productFactory,
        \Magento\Checkout\Helper\Data $checkoutHelper,
        CreditMemeotems $creditMemo
    )
    {
        parent::__construct($context);
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->order = $order;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        $this->country = $country;
        $this->logger = $logger;
        $this->_productFactory = $productFactory;
        $this->_checkoutHelper = $checkoutHelper;
        $this->creditMemo = $creditMemo;
    }

    /**
     * Export data provider to CSV
     *
     * @return \Magento\Framework\App\ResponseInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=sales_orders_export.csv');

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');
        fputcsv($output, [
            'Report date',
            'Order Type',
            'Order Id',
            'Order ID (Global-E)',
            'Customer Name',
            'Billing Address',
            'Shipping Address',
            'Purchase Country',
            'Customer Email',
            'Logged In',
            'Return Customer?',
            'Product Name',
            'Product Colour',
            'Product Size',
            'SKU',
            'Season',
            'Units Ordered',
            'Order total',
            'Taxes',
            'Promotion/Discount Value',
            'Promotion Name',
            'Subtotal',
            'Shipping Cost',
            'Grand total'
        ]);

        // get order collection
        $rows = $this->getOrderCollection();
        $values = [];
        foreach ($rows as $row) {

            /**
             * @var \Magento\Sales\Model\Order $row
             */
            $id = $row->getId();

            $type = $row->getBaseTotalRefunded() ? 'refunded' : $row->getEntityType();
            $orderDate = $row->getCreatedAt();
            $formattedDate = date('Y-m-d', strtotime($orderDate));
            $orderId = $row->getIncrementId();
            $orderGlobaleId = $row->getExtOrderId();
            $orderCustomerName = $row->getCustomerFirstname() . ' ' . $row->getCustomerLastname();
            $orderCustomerEmail = $row->getCustomerEmail();
            $oldOrder = $this->_orderCollectionFactory->create()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('customer_email',$orderCustomerEmail)
                ->count();
            $countryName = '';
            $orderBillingAddress = null;
            if ($row->getBillingAddress()) {
                $orderBillingAddress = $row->getBillingAddress();
                $countryCode = $orderBillingAddress->getCountryId();
                $country = $this->country->loadByCode($countryCode);
                $countryName = $country->getName();

            } else {
                $orderBillingAddress = '';
            }

            $orderShippingAddress = $row->getShippingAddress();

            $customerId = $row->getCustomerId();
            if (isset($customerId)) {
                $loggedIn = 'Y';
            } else {
                $loggedIn = 'N';
            }
            $shippingAddress = $row->getShippingAddress();
            $orderGrandTotal = $row->getBaseTotalRefunded() ? $row->getBaseTotalRefunded() : $row->getBaseGrandTotal();
            $orderSubTotal = $row->getBaseSubtotalRefunded() ? $row->getBaseSubtotalRefunded() : $row->getBaseSubtotal();
            $orderShippingAmount = $row->getBaseShippingRefunded() ? $row->getBaseShippingRefunded() : $row->getBaseShippingAmount();
            $orderDiscountAmount = '';
            if ($row->getBaseDiscountAmount()) {
                $orderDiscountAmount = $row->getBaseDiscountRefunded() ? abs($row->getBaseDiscountRefunded()) : abs($row->getBaseDiscountAmount());
            }
            $promotionName = $row->getCouponCode();
            /**
             * @var \Magento\Sales\Model\Order $row
             */
            $lines = $row->getAllVisibleItems();
            $value = [];

            //loop through order items
            foreach ($lines as $line) {
                /**
                 * @var \Magento\Sales\Model\Order\Item $line
                 */
                $name = $line->getName();
                $sku = $this->_productFactory->create()->load($line->getProductId())->getSku();
                $price = $line->getPrice();
                $qty = $line->getQtyOrdered();
                $productOptions = $line->getProductOptions();
                $season = $this->getProductAttribute($line->getProductId(), 'default_collection');
                $color = '';
                $size = '';
                if (isset($productOptions['attributes_info'])) {
                    foreach ($productOptions['attributes_info'] as $option) {
                        if ($option['label'] == 'Colour') {
                            $color = $option['value'];
                        }
                        if ($option['label'] == 'Size') {
                            $size = $option['value'];
                        }
                    }
                }
                $value = [
                    $formattedDate,
                    'order',
                    $orderId,
                    $orderGlobaleId,
                    $orderCustomerName,
                    $orderBillingAddress->getData("firstname") . '/' .
                    $orderBillingAddress->getData("lastname") . "/" .
                    $orderBillingAddress->getData("company") . "/" .
                    $orderBillingAddress->getData("street") . "/" .
                    $orderBillingAddress->getData("city") . "/" .
                    $orderBillingAddress->getData("region") . "/" .
                    $orderBillingAddress->getData("country_id") . "/" .
                    $orderBillingAddress->getData("postcode") . "/" .
                    $orderBillingAddress->getData("telephone"),
                    $shippingAddress->getData("firstname") . '/' .
                    $shippingAddress->getData("lastname") . "/" .
                    $shippingAddress->getData("company") . "/" .
                    $shippingAddress->getData("street") . "/" .
                    $shippingAddress->getData("city") . "/" .
                    $shippingAddress->getData("region") . "/" .
                    $shippingAddress->getData("country_id") . "/" .
                    $shippingAddress->getData("postcode") . "/" .
                    $shippingAddress->getData("telephone"),
                    $countryName,
                    $orderCustomerEmail,
                    $loggedIn,
                    $oldOrder ? 'Y' : 'N',
                    $name,
                    $color,
                    $size,
                    $sku,
                    $season,
                    $line->getQtyOrdered(),
                    $line->getBaseOriginalPrice(),
                    $line->getBaseTaxRefunded() ?  $line->getBaseTaxRefunded() : $line->getBaseTaxAmount(),
                    $line->getBaseDiscountAmount(),
                    $promotionName,
                    $this->_checkoutHelper->getBaseSubtotalInclTax($line),
                    $orderShippingAmount,
                    $line->getBaseDiscountAmount() ? $this->_checkoutHelper->getBaseSubtotalInclTax($line) - $line->getBaseDiscountAmount() : $line->getBaseRowTotal()
                ];
                fputcsv($output, $value);
            }

            $creditMemo =  $row->getCreditmemosCollection();

            if ($creditMemo) {
                foreach ($row->getCreditmemosCollection() as $item){

                    $line = $this->creditMemo->load($item->getId(), 'parent_id');

                    $name = $line->getName();
                    $sku = $this->_productFactory->create()->load($line->getProductId())->getSku();
                    $price = $line->getPrice();
                    $qty = $line->getQtyOrdered();
                    $productOptions = $line->getProductOptions();
                    $season = $this->getProductAttribute($line->getProductId(), 'default_collection');
                    $color = '';
                    $size = '';
                    if (isset($productOptions['attributes_info'])) {
                        foreach ($productOptions['attributes_info'] as $option) {
                            if ($option['label'] == 'Colour') {
                                $color = $option['value'];
                            }
                            if ($option['label'] == 'Size') {
                                $size = $option['value'];
                            }
                        }
                    }
                    $value = [
                        $formattedDate,
                        'refunded',
                        $orderId,
                        $orderGlobaleId,
                        $orderCustomerName,
                        $orderBillingAddress->getData("firstname") . '/' .
                        $orderBillingAddress->getData("lastname") . "/" .
                        $orderBillingAddress->getData("company") . "/" .
                        $orderBillingAddress->getData("street") . "/" .
                        $orderBillingAddress->getData("city") . "/" .
                        $orderBillingAddress->getData("region") . "/" .
                        $orderBillingAddress->getData("country_id") . "/" .
                        $orderBillingAddress->getData("postcode") . "/" .
                        $orderBillingAddress->getData("telephone"),
                        $shippingAddress->getData("firstname") . '/' .
                        $shippingAddress->getData("lastname") . "/" .
                        $shippingAddress->getData("company") . "/" .
                        $shippingAddress->getData("street") . "/" .
                        $shippingAddress->getData("city") . "/" .
                        $shippingAddress->getData("region") . "/" .
                        $shippingAddress->getData("country_id") . "/" .
                        $shippingAddress->getData("postcode") . "/" .
                        $shippingAddress->getData("telephone"),
                        $countryName,
                        $orderCustomerEmail,
                        $loggedIn,
                        $oldOrder ? 'Y' : 'N',
                        $name,
                        $color,
                        $size,
                        $sku,
                        $season,
                        $line->getQty(),
                        $line->getBasePriceInclTax(),
                        $line->getBaseTaxRefunded() ?  $line->getBaseTaxRefunded() : $line->getBaseTaxAmount(),
                        $line->getBaseDiscountAmount(),
                        $promotionName,
                        $this->_checkoutHelper->getBaseSubtotalInclTax($line),
                        $orderShippingAmount,
                        $line->getBaseRowTotalInclTax() ? $this->_checkoutHelper->getBaseSubtotalInclTax($line) - $line->getBaseDiscountAmount() : $line->getBaseRowTotal()
                    ];
                    fputcsv($output, $value);
                }
            }
        }
    }

    /**
     * Get order collection object
     *
     * @return \Magento\Sales\Model\ResourceModel\Order\Collection
     */
    public function getOrderCollection()
    {
        try {
            $filters = $this->getRequest()->getParam('filters');
            $selected = $this->getRequest()->getParam('selected');

            $created_at = $filters['created_at'] ?? null;
            $this->logger->info('All filters data:', $this->getRequest()->getParams());

            if (isset($filters['created_at']) && $selected == 'false') {
                $createdAtFilters = $filters['created_at'];
                $fromDate = date('Y-m-d', strtotime($createdAtFilters['from']));
                $toDate = date('Y-m-d', strtotime($createdAtFilters['to'] . ' +1 day'));

                $collection = $this->_orderCollectionFactory->create()
                    ->addAttributeToSelect('*')
                    ->addAttributeToFilter('created_at', ['from' => $fromDate, 'to' => $toDate]);
            } elseif (isset($filters['created_at']) && $selected !== 'false' && !empty($selected)) {
                $collection = $this->_orderCollectionFactory->create()
                    ->addAttributeToSelect('*')
                    ->addAttributeToFilter('entity_id', ['in' => $selected]);
            } else {
                $collection = $this->_orderCollectionFactory->create()
                    ->addAttributeToSelect('*');
            }
            return $collection;
        } catch (\Exception $e){
            $this->logger->info('Exception: ', $e->getMessage());
        }

    }

    public function getProductAttribute($productId, $attribute)
    {
        return $this->_productFactory->create()->load($productId)->getAttributeText($attribute);
    }
}
