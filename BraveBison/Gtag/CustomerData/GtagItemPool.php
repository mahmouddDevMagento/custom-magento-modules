<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace BraveBison\Gtag\CustomerData;

use Magento\Catalog\Helper\Image;
use Magento\Catalog\Helper\Product\ConfigurationPool;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Product\Configuration\Item\ItemResolverInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Checkout\CustomerData\AbstractItem;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;
use Magento\Msrp\Helper\Data;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Default cart item
 */
class GtagItemPool extends AbstractItem
{
    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    /**
     * @var \Magento\Msrp\Helper\Data
     */
    protected $msrpHelper;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \Magento\Catalog\Helper\Product\ConfigurationPool
     */
    protected $configurationPool;

    /**
     * @var \Magento\Checkout\Helper\Data
     */
    protected $checkoutHelper;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;
    /**
     * @var ProductFactory
     */
    protected $_productFactory;
    /**
     * @var \Magento\Framework\Escaper
     */
    private $escaper;
    /**
     * @var ItemResolverInterface
     */
    private $itemResolver;

    /**
     * @param Image $imageHelper
     * @param Data $msrpHelper
     * @param UrlInterface $urlBuilder
     * @param ConfigurationPool $configurationPool
     * @param \Magento\Checkout\Helper\Data $checkoutHelper
     * @param Escaper|null $escaper
     * @param ItemResolverInterface|null $itemResolver
     * @param StoreManagerInterface $storeManager
     * @param CategoryFactory $categoryFactory
     * @param ProductFactory $_productFactory
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Catalog\Helper\Image                     $imageHelper,
        \Magento\Msrp\Helper\Data                         $msrpHelper,
        \Magento\Framework\UrlInterface                   $urlBuilder,
        \Magento\Catalog\Helper\Product\ConfigurationPool $configurationPool,
        \Magento\Checkout\Helper\Data                     $checkoutHelper,
        \Magento\Framework\Escaper                        $escaper = null,
        ItemResolverInterface                             $itemResolver = null,
        \Magento\Store\Model\StoreManagerInterface        $storeManager,
        \Magento\Catalog\Model\CategoryFactory            $categoryFactory,
        \Magento\Catalog\Model\ProductFactory             $_productFactory
    )
    {
        $this->configurationPool = $configurationPool;
        $this->imageHelper = $imageHelper;
        $this->msrpHelper = $msrpHelper;
        $this->urlBuilder = $urlBuilder;
        $this->checkoutHelper = $checkoutHelper;
        $this->escaper = $escaper ?: ObjectManager::getInstance()->get(\Magento\Framework\Escaper::class);
        $this->itemResolver = $itemResolver ?: ObjectManager::getInstance()->get(ItemResolverInterface::class);
        $this->_storeManager = $storeManager;
        $this->_productFactory = $_productFactory;
        $this->_categoryFactory = $categoryFactory;
    }

    /**
     * @param int $productId
     * @return void
     */
    public function getParentProduct($productId)
    {

        $product = $this->_productFactory->create()->load($productId);
        /**
         * @var \Magento\Catalog\Model\Product $product
         */
        return $product->getSku();
    }

    /**
     * @inheritdoc
     */
    protected function doGetItemData()
    {
        $imageHelper = $this->imageHelper->init($this->getProductForThumbnail(), 'mini_cart_product_thumbnail');
        $productName = $this->escaper->escapeHtml($this->item->getProduct()->getName());
        $parentItem = $this->item->getParentItem();
        return [
            'options' => $this->getOptionList(),
            'qty' => $this->item->getQty() * 1,
            'item_id' => $this->item->getId(),
            'configure_url' => $this->getConfigureUrl(),
            'is_visible_in_site_visibility' => $this->item->getProduct()->isVisibleInSiteVisibility(),
            'product_id' => $this->item->getProduct()->getId(),
            'product_name' => $productName,
            'product_sku' => $this->item->getProduct()->getSku(),
            'product_url' => $this->getProductUrl(),
            'product_has_url' => $this->hasProductUrl(),
            'product_price' => $this->checkoutHelper->formatPrice($this->item->getCalculationPrice()),
            'product_price_value' => $this->item->getCalculationPrice(),
            'product_image' => [
                'src' => $imageHelper->getUrl(),
                'alt' => $imageHelper->getLabel(),
                'width' => $imageHelper->getWidth(),
                'height' => $imageHelper->getHeight(),
            ],
            'currencyCode' => $this->_storeManager->getStore()->getCurrentCurrencyCode(),
            'category' => $this->getProductCategory($this->item->getProduct()),
            'canApplyMsrp' => $this->msrpHelper->isShowBeforeOrderConfirm($this->item->getProduct())
                && $this->msrpHelper->isMinimalPriceLessMsrp($this->item->getProduct()),
            'message' => $this->item->getMessage(),
            'parent_product_sku' => $this->getParentProduct($this->item->getProduct()->getId())
        ];
    }

    /**
     * Returns product for thumbnail.
     *
     * @return \Magento\Catalog\Model\Product
     * @codeCoverageIgnore
     */
    protected function getProductForThumbnail()
    {
        return $this->itemResolver->getFinalProduct($this->item);
    }

    /**
     * Returns product.
     *
     * @return \Magento\Catalog\Model\Product
     * @codeCoverageIgnore
     */
    protected function getProduct()
    {
        return $this->item->getProduct();
    }

    /**
     * Get list of all options for product
     *
     * @return array
     * @codeCoverageIgnore
     */
    protected function getOptionList()
    {
        return $this->configurationPool->getByProductType($this->item->getProductType())->getOptions($this->item);
    }

    /**
     * Get item configure url
     *
     * @return string
     */
    protected function getConfigureUrl()
    {
        return $this->urlBuilder->getUrl(
            'checkout/cart/configure',
            ['id' => $this->item->getId(), 'product_id' => $this->item->getProduct()->getId()]
        );
    }

    /**
     * Retrieve URL to item Product
     *
     * @return string
     */
    protected function getProductUrl()
    {
        if ($this->item->getRedirectUrl()) {
            return $this->item->getRedirectUrl();
        }

        $product = $this->item->getProduct();
        $option = $this->item->getOptionByCode('product_type');
        if ($option) {
            $product = $option->getProduct();
        }

        return $product->getUrlModel()->getUrl($product);
    }

    /**
     * Check Product has URL
     *
     * @return bool
     */
    protected function hasProductUrl()
    {
        if ($this->item->getRedirectUrl()) {
            return true;
        }

        $product = $this->item->getProduct();
        $option = $this->item->getOptionByCode('product_type');
        if ($option) {
            $product = $option->getProduct();
        }

        if ($product->isVisibleInSiteVisibility()) {
            return true;
        } else {
            if ($product->hasUrlDataObject()) {
                $data = $product->getUrlDataObject();
                if (in_array($data->getVisibility(), $product->getVisibleInSiteVisibilities())) {
                    return true;
                }
            }
        }

        return false;
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
            $categoryId = $categoryIds[0];
            $category = $this->_categoryFactory->create()->load($categoryId);
            return $category->getName();

        }
        return null;
    }
}
