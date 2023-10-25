<?php

namespace BraveBison\Gtag\Observer;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\Checkout\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\CategoryFactory;
use Psr\Log\LoggerInterface;

/**
 * Class RemoveFromCart
 * @package BraveBison\Gtag\Observer
 */
class RemoveFromCart implements ObserverInterface
{
    /**
     * @var ProductFactory
     */
    protected $_productFactory;

    /**
     * @var Session
     */
    protected $_checkoutSession;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * RemoveFromCart constructor.
     *
     * @param ProductFactory $productFactory
     * @param Session $checkoutSession
     *
     */
    public function __construct(
        ProductFactory $productFactory,
        Session $checkoutSession,
        StoreManagerInterface $storeManager,
        CategoryFactory $categoryFactory,
        LoggerInterface $logger,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->_productFactory = $productFactory;
        $this->_checkoutSession  = $checkoutSession;
        $this->storeManager=$storeManager;
        $this->_categoryFactory = $categoryFactory;
        $this->logger = $logger;
        $this->request = $request;
    }

    /**
     * Catch remove from cart event
     *
     * @param Observer $observer
     *
     * @return $this|void
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        if (!$this->request->isXmlHttpRequest()){
            $quoteItem = $observer->getData('quote_item');
            $qty       = $quoteItem->getQty();
            if ($quoteItem->getProductType() === 'configurable') {
                $selectedProduct = $this->_productFactory->create();
                $selectedProduct->load($selectedProduct->getIdBySku($quoteItem->getSku()));
                $this->setGTAGRemoveFromCartData($selectedProduct, $qty);
            } else {
                $this->setGTAGRemoveFromCartData($quoteItem, $qty);
            }
        }
        
        return $this;
    }

    public function getParentProduct($productId){
        $product = $this->_productFactory->create()->load($productId);
        return $product->getSku();
    }
    /**
     * Get GTAG checkout session
     *
     * @return Session
     */
    public function getSessionManager()
    {
        return $this->_checkoutSession;
    }

    /**
     * Set Google Tag Manage RemoveFromCart event
     *
     * @param Product $product
     * @param float $quantity
     *
     * @return array
     * @throws NoSuchEntityException
     */
    public function getGTAGRemoveFromCartData($product, $quantity)
    {
        $productData = [];
        $productData['id']    =  $this->getParentProduct($product->getId());
        $productData['variant']   = $product->getSku();
        $productData['name']  = $product->getName();
        $productData['price'] = $product->getPrice();
        $productData['category'] = $this->getProductCategory($product);
        $productData['quantity'] = $quantity;
        $currencyCode = $this->getCurrentCurrency();

        $data = [
            'event'     => 'remove_from_cart',
            'ecommerce' => [
                'currencyCode' => $currencyCode,
                'remove'       => [
                    'products' => [$productData]
                ]
            ]
        ];
        return $data;
    }
    /**
     * Set Google Tag Manage RemoveFromCart event
     *
     * @param Product $product
     * @param float $qty
     *
     * @throws NoSuchEntityException
     */
    protected function setGTAGRemoveFromCartData($product, $qty)
    {
        $this->getSessionManager()->unsGTAGRemoveFromCartData();
        $this->getSessionManager()->setGTAGRemoveFromCartData($this->getGTAGRemoveFromCartData(
            $product,
            $qty
        ));
    }

    /**
     * Retrieves symbol of the current currency
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getCurrentCurrency()
    {
        return $this->storeManager->getStore()->getCurrentCurrencyCode();
    }

    /**
     * Retrieves category of product
     *
     * @return string
     */
    public function getProductCategory($product)
    {
        // Get the product category
        $categoryIds = $product->getCategoryIds();
        if (!empty($categoryIds)) {
            $this->logger->info('categories product ids:', $categoryIds);
            $categoryId = $categoryIds[0];
            $category = $this->_categoryFactory->create()->load($categoryId);
            return $category->getName();

        }
        return null;
    }

}
