<?php

namespace BestResponseMedia\CurrencyConversion\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use BestResponseMedia\CurrencyConversion\Model\Logger\CurrencyConversionLogger;

class PricesImport extends \Magento\ImportExport\Model\Import
{
    /**
     * Run import through cron
     *
     * @param $sourceFile
     * @return array
     */
    public function runCustomImport($sourceFile)
    {
        $messages = [
            'errorMsg' => [],
            'errorRows' => []
        ];


        if ($sourceFile) {
            $validationStrategy = ProcessingErrorAggregatorInterface::VALIDATION_STRATEGY_STOP_ON_ERROR;

            $this->setData(self::FIELD_NAME_VALIDATION_STRATEGY, $validationStrategy);
            $this->setData('behavior', self::BEHAVIOR_ADD_UPDATE);
            $this->setData('entity', 'catalog_product');
            $this->_removeBom($sourceFile);
            $this->createHistoryReport($sourceFile, 'catalog_product');

            $result = $this->validateSource(
                \Magento\ImportExport\Model\Import\Adapter::findAdapterFor(
                    $sourceFile,
                    $this->_filesystem->getDirectoryWrite(DirectoryList::VAR_IMPORT_EXPORT),
                    $this->getData(\Magento\ImportExport\Model\Import::FIELD_FIELD_SEPARATOR)
                )
            );
//            var_dump($sourceFile);
//            return ;
            /**
             * @var \Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface $errorAggregator
             */
            $errorAggregator = $this->getErrorAggregator();
            if ($result && !$errorAggregator->hasFatalExceptions()) {
                $errorAggregator->clear();
                $result = $this->importSource();
            }else {
                $rowMessages = $errorAggregator->getRowsGroupedByErrorCode([], ['systemException']);
                foreach ($rowMessages as $errorCode => $rows) {
                    $messages['errorMsg'] [] = $errorCode . ' ' . __('in rows:') . ' ' . implode(', ', $rows);
                    $messages['errorRows'] [] = $rows;
                }
            }
            if ($result) {
                $this->invalidateIndex();
            }
            return $messages;
        }
        return $messages;
    }


}