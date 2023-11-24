<?php

namespace UnionCoop\MagentoTask\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Checkout\Model\Cart;
use Magento\Checkout\Model\Session;
use Magento\CatalogInventory\Api\StockStateInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\UrlInterface;

class CartItemsPopup implements ObserverInterface
{

    protected $productRepository;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var StockStateInterface
     */
    protected $stockState;

    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @param RequestInterface $request
     * @param Session $session
     * @param StockStateInterface $stockState
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        RequestInterface $request,
        Session $session,
        StockStateInterface $stockState,
        ManagerInterface $messageManager,
        Image $imageHelper,
        ProductRepositoryInterface $productRepository,
        UrlInterface $urlBuilder,
        Cart $cart

    ) {
        $this->request = $request;
        $this->session = $session;
        $this->stockState = $stockState;
        $this->messageManager = $messageManager;
        $this->imageHelper = $imageHelper;
        $this->productRepository = $productRepository;
        $this->_urlBuilder = $urlBuilder;
        $this->cart = $cart;

    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $controller = $observer->getControllerAction();
        $fullActionName = $controller->getRequest()->getFullActionName();

        if ($fullActionName == 'checkout_cart_index') {
            $items = $this->cart->getQuote()->getAllVisibleItems();

            $outOfStockItems = [];

            foreach ($items as $item) {
                $product = $item->getProduct();
                $childProduct_real_id= '';
                if ($product->getTypeId() === \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
                    if ($option = $item->getOptionByCode('simple_product')) {
                        $simpleProductRealId =  $option->getProduct()->getId();
                    }
                    $childProducts = $product->getTypeInstance()->getUsedProducts($product);
                    foreach ($childProducts as $childProduct) {
                        $childId = $childProduct->getId();
                        if ($simpleProductRealId == $childId){
                            $stockQty = $this->stockState->getStockQty($childId);
                            $image = $this->imageHelper->init($childProduct, 'product_thumbnail_image')->getUrl();
                            $productUrl = $childProduct->getProductUrl();
                            $sku = $childProduct->getSku();
                            if ($stockQty <= 0) {
                                $outOfStockItems[] = [
                                    'id' => $item->getId(),
                                    'name' => $childProduct->getName(),
                                    'image' => $image,
                                    'url' => $productUrl,
                                    'sku' => $sku,
                                ];
                            }
                        }

                    }
                } else {
                    $productId = $item->getProduct()->getId();
                    $productStock = $this->stockState->getStockQty($productId);
                    $product = $this->productRepository->getById($productId);
                    $image = $this->imageHelper->init($product, 'product_thumbnail_image')->getUrl();
                    $productUrl = $product->getProductUrl();
                    $sku = $product->getSku();
                    if ($productStock <= 0) {
                        $outOfStockItems[] = [
                            'id' => $item->getId(),
                            'name' => $item->getName(),
                            'image' => $image,
                            'url' => $productUrl,
                            'sku' => $sku,
                        ];
                    }
                }
            }

            if (!empty($outOfStockItems)) {
                $this->triggerPopup($outOfStockItems);
            }
        }
    }


    public function triggerPopup($outOfStockItems)
    {
        $popupContent = '<div class="out-of-stock-popup">';
        $popupContent .= '<h2>Out of Stock Items</h2>';

        foreach ($outOfStockItems as $item) {
            $popupContent .= '<form action="' . $this->_urlBuilder->getUrl('unioncoop/index/removeFromCart') . '" method="post">';
            $popupContent .= '<input type="hidden" name="id" value="' . $item['id'] . '">';
            $popupContent .= '<div class="out-of-stock-item">';
            $popupContent .= '<img src="' . $item['image'] . '" alt="' . $item['name'] . '">';
            $popupContent .= '<h3>' . $item['name'] . '</h3>';
            $popupContent .= '<p>ID: ' . $item['id'] . '</p>';
            $popupContent .= '<p>SKU: ' . $item['sku'] . '</p>';
            $popupContent .= '<a href="' . $item['url'] . '" target="_blank">View Product</a>';
            $popupContent .= '<button type="submit">Submit</button>';
            $popupContent .= '</div>';
            $popupContent .= '</form>';
        }

        $popupContent .= '</div>';

        $this->messageManager->addWarning($popupContent);
    }
}


