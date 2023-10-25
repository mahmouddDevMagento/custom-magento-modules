<?php

namespace BestResponseMedia\CurrencyConversion\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use \Psr\Log\LoggerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use BestResponseMedia\CurrencyConversion\Model\Logger\CurrencyConversionLogger;

class Data extends AbstractHelper
{
    /**
     * @var CurrencyConversionLogger
     */
    protected $logger;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;


    public function __construct(
        CurrencyConversionLogger $logger,
        StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList

    ){
        $this->logger = $logger;
        $this->storeManager = $storeManager;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productRepository = $productRepository;
        $this->directoryList = $directoryList;


    }

    public function getEurCurrencyRate(){
        return $this->storeManager->getStore()->getBaseCurrency()->getRate('EUR');

    }

    public function getUsdCurrencyRate(){
        return $this->storeManager->getStore()->getBaseCurrency()->getRate('USD');

    }

    public function getProductCollectionByStoreId($storeId)
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addStoreFilter($storeId);
        $collection->addAttributeToSelect('sku');
        $collection->addAttributeToSelect('price');
//            ->getSelect()->limit(10);

        $result = [];
        foreach ($collection as $product) {
            $result[] = [
                'sku' => $product->getSku(),
                'store_view_code' => ' ',
                'price' => $product->getFinalPrice()
            ];
        }

        return $result;
    }

    /**
     * Get US prices array based on default store product prices * rate
     *
     * Store id is the default store id "uk"
     *
     * @param $storeId
     * @return array
     */
    public function getUsdPricesArray($storeId)
    {
        $currencyRate = $this->getUsdCurrencyRate();
        $productCollection = $this->getProductCollectionByStoreId($storeId);

        $usdPricesArray = [];
        foreach ($productCollection as $product) {
            $usdPrice = $product['price'] * $currencyRate;
            $usdPricesArray[] = [
                'sku' => $product['sku'],
                'store_view_code' => 'us',
                'price' => $usdPrice
            ];
        }

        return $usdPricesArray;
    }

    /**
     * Get EU prices array based on default store product prices * rate
     *
     * Store id is the default store id "uk"
     *
     * @param $storeId
     * @return array
     */
    public function getEurPricesArray($storeId)
    {
        $currencyRate = $this->getEurCurrencyRate();
        $productsCollection = $this->getProductCollectionByStoreId($storeId);

        $eurPricesArray = [];
        foreach ($productsCollection as $product) {
            $eurPrice = $product['price'] * $currencyRate;
            $eurPricesArray[] = [
                'sku' => $product['sku'],
                'store_view_code' => 'eu',
                'price' => $eurPrice
            ];
        }

        return $eurPricesArray;
    }

    public function updateUsdPrices()
    {
        $usdPricesArray = $this->getUsdPricesArray(1);

        $usdStore = $this->storeManager->getStore('us');
        $usdStoreId = $usdStore->getId();
//        return $usdStoreId;

        foreach ($usdPricesArray as $productData) {
            $sku = $productData['sku'];
            $newPrice = $productData['price'];

            try {
                $product = $this->productRepository->get($sku, false, $usdStoreId);
                $product->setPrice($newPrice);
                $product->setFinalPrice($newPrice);
                $this->productRepository->save($product);
//                return 'products prices updated successfully';
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }

    public function updateEurPrices()
    {
        $eurPricesArray = $this->getEurPricesArray(1);

        $eurStore = $this->storeManager->getStore('eu');
        $eurStoreId = $eurStore->getId();
//        return $eurStoreId;

        foreach ($eurPricesArray as $productData) {
            $sku = $productData['sku'];
            $newPrice = $productData['price'];

            try {
                $product = $this->productRepository->get($sku, false, $eurStoreId);
                $product->setPrice($newPrice);
                $product->setFinalPrice($newPrice);
                $this->productRepository->save($product);
//                return 'products prices updated successfully';

            }  catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }


    //the second method to update prices through csv
    public function generateCsv($data, $filename)
    {
        $varDirectory = $this->directoryList->getPath('var');
        $csvDirectory = $varDirectory . '/pricesCsv';
        $filePath = $csvDirectory . '/' . $filename;

        if (!is_dir($csvDirectory)) {
            mkdir($csvDirectory, 0777, true);
        }

        $csvFile = fopen($filePath, 'w');
        fputcsv($csvFile, array('sku', 'store_view_code','price'));

        foreach ($data as $row) {
            fputcsv($csvFile, $row);
        }

        fclose($csvFile);

        return $filePath;
    }

    public function generateUsdPricesCsv($storeId)
    {
        $usdPricesArray = $this->getUsdPricesArray($storeId);
        $filePath = $this->generateCsv($usdPricesArray, 'usd_prices.csv');

        return $filePath;
    }

    public function generateEurPricesCsv($storeId)
    {
        $eurPricesArray = $this->getEurPricesArray($storeId);
        $filePath = $this->generateCsv($eurPricesArray, 'eur_prices.csv');

        return $filePath;
    }

    protected function getStoreViewCode($storeId)
    {
        return $this->storeManager->getStore($storeId)->getCode();
    }

    public function compareBySku($row1, $row2) {
        $sku1 = $row1['sku'];
        $sku2 = $row2['sku'];

        $store1 = isset($row1['store_view_code']) ? $row1['store_view_code'] : '';
        $store2 = isset($row2['store_view_code']) ? $row2['store_view_code'] : '';

        $skuCompare = strcmp($sku1, $sku2);

        if ($skuCompare == 0) {
            return strcmp($store2, $store1);
        }

        return $skuCompare;
    }

    public function sortArrayBySku() {
        $mergedPricesArray = array_merge($this->getUsdPricesArray(1), $this->getEurPricesArray(1));

        usort($mergedPricesArray, array($this, "compareBySku"));
        return $mergedPricesArray;
    }
}