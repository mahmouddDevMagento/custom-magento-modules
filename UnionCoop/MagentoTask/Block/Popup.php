<?php
namespace UnionCoop\MagentoTask\Block;

use Magento\CatalogInventory\Api\StockStateInterface;
use Magento\Checkout\Model\Cart;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Psr\Log\LoggerInterface;

class Popup extends \Magento\Framework\View\Element\Template
{
    /**
     * @var StockStateInterface
     */
    protected $stockState;

    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @var Image
     */
    protected $imageHelper;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param StockStateInterface $stockState
     * @param Cart $cart
     * @param Image $imageHelper
     * @param ProductRepositoryInterface $productRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        StockStateInterface $stockState,
        Cart $cart,
        Image $imageHelper,
        ProductRepositoryInterface $productRepository,
        LoggerInterface $logger,
        array $data = []
    ) {
        $this->stockState = $stockState;
        $this->cart = $cart;
        $this->imageHelper = $imageHelper;
        $this->productRepository = $productRepository;
        $this->logger = $logger;
        parent::__construct($context, $data);
    }

    public function getOutOfStockItems()
    {
        $items = $this->cart->getQuote()->getAllVisibleItems();
        $outOfStockItems = [];

        foreach ($items as $item) {
            try {
                $product = $item->getProduct();
                if ($product->getTypeId() === \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
                    $childProduct = $item->getOptionByCode('simple_product')->getProduct();
                    $childId = $childProduct->getId();
                    $stockQty = $this->getStockQty($childId);
                    $image = $this->getProductImage($childProduct);
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
                } else {
                    $productId = $item->getProduct()->getId();
                    $productStock = $this->getStockQty($productId);
                    $product = $this->productRepository->getById($productId);
                    $image = $this->getProductImage($product);
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
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());

            }
        }

        return $outOfStockItems;
    }

    protected function getStockQty($productId) {
       return $this->stockState->getStockQty($productId);
    }

    protected function getProductImage($product){
        return $this->imageHelper->init($product, 'product_thumbnail_image')->getUrl();
    }

}
