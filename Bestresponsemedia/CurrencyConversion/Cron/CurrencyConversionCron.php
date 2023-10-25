<?php
Namespace BestResponseMedia\CurrencyConversion\Cron;

use BestResponseMedia\CurrencyConversion\Helper\Data;
use BestResponseMedia\CurrencyConversion\Model\PricesImport;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\ValidatorException;
use BestResponseMedia\CurrencyConversion\Model\Logger\CurrencyConversionLogger;


class CurrencyConversionCron{

    /**
     * @var CurrencyConversionLogger
     */
    protected $logger;

    /**
     * @var Data
     */
    protected $dataHelper;

    /**
     * @var PricesImport
     */
    protected $import;


    public function __construct(
        CurrencyConversionLogger $logger,
        Data $dataHelper,
        PricesImport $import

    ) {
        $this->logger = $logger;
        $this->dataHelper = $dataHelper;
        $this->import = $import;

    }

    public function execute()
    {

        echo "Running cron job brm_currency_conversion for update prices\n";
        $this->logger->info('Product prices update Cron started');

//        first method to update prices
//        try {
//            $this->dataHelper->updateUsdPrices();
//            $this->logger->info('products prices in USD store updated successfully');
//
//        } catch (\Exception $e) {
//            $this->logger->info($e->getMessage());
//        }
//
//        try {
//            $this->dataHelper->updateEurPrices();
//            $this->logger->info('products prices in EUR store updated successfully');
//
//        } catch (\Exception $e) {
//            $this->logger->info($e->getMessage());
//        }
//        return ;


        //second method to update prices using csv import
//        $this->logger->info('all product prices by store' . print_r($this->dataHelper->sortArrayBySku(), true));
        try {
            $sortedPricesArray= $this->dataHelper->sortArrayBySku();
            $pricesCsv = $this->dataHelper->generateCsv($sortedPricesArray, 'prices.csv');
            if ($pricesCsv){
                $importMessages = $this->import->runCustomImport($pricesCsv);
                if (!empty($importMessages['errorMsg'])) {
                    $this->logger->info($importMessages['errorMsg']);
                } else {
                    $this->logger->info('product prices imported successfully for all stores');
                }
            }
        }catch (Exception | FileSystemException | ValidatorException $e) {
            $this->logger->info($e->getMessage());
        }

        $this->logger->info('Product prices update Cron finished');

    }
}