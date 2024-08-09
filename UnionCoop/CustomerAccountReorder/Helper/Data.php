<?php

namespace Unioncoop\CustomerAccountReorder\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Sales\Model\OrderFactory;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Helper\Image;
use Magento\CatalogInventory\Api\StockRegistryInterface;

class Data extends AbstractHelper
{
    /**
     * @var OrderFactory
     */
    protected $orderFactory;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var Image
     */
    protected $imageHelper;

    /**
     * @var StockRegistryInterface
     */
    protected $stockRegistry;

    public function __construct(
        OrderFactory $orderFactory,
        ProductRepository $productRepository,
        Image $imageHelper,
        StockRegistryInterface $stockRegistry
    ) {
        $this->orderFactory = $orderFactory;
        $this->productRepository = $productRepository;
        $this->imageHelper = $imageHelper;
        $this->stockRegistry = $stockRegistry;
    }

    public function getOrderItems($orderId)
    {
        $order = $this->orderFactory->create()->load($orderId);
        $orderItems = [];
        $currency = $order->getOrderCurrency()->getCurrencySymbol();

        foreach ($order->getAllVisibleItems() as $item) {
            $product = $item->getProduct();

            if (!$product || !$product->getStatus()) {
                continue;
            }

            $productId = $item->getProduct()->getId();
            $productImage = $this->getProductImage($product);
            $isOutOfStock = $this->isProductOutOfStock($product);

            $orderItems[] = [
                'id' => $productId,
                'name' => $item->getName(),
                'image' => $productImage,
                'price' => $item->getPrice(),
                'currency' => $currency,
                'qty' => !empty((float)$item->getOriginalQtyOrdered()) ? $item->getOriginalQtyOrdered() : $item->getQtyOrdered(),
                'out_of_stock' => $isOutOfStock
            ];
        }
        return $orderItems;
    }

    protected function getProductImage($product)
    {
        return $this->imageHelper->init($product, 'product_thumbnail_image')->getUrl();
    }

    protected function isProductOutOfStock($product)
    {
        return !$product->isSalable();
    }

}
