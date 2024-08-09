<?php
namespace Unioncoop\TamayazRedemption\Model\RedemptionFactor;

use Unioncoop\TamayazRedemption\Model\ResourceModel\RedemptionFactor\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager,
        UrlInterface $urlBuilder,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $tamayazRedemptionFactor) {
            $tamayazRedemptionFactorData = $tamayazRedemptionFactor->getData();
            $this->loadedData[$tamayazRedemptionFactor->getId()] = $tamayazRedemptionFactorData;
        }
        return $this->loadedData;
    }
}
