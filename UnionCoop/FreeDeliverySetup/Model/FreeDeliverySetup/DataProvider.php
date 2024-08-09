<?php
namespace Unioncoop\FreeDeliverySetup\Model\FreeDeliverySetup;

use Unioncoop\FreeDeliverySetup\Model\ResourceModel\FreeDeliverySetup\CollectionFactory;
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
        CollectionFactory $freeDeliverySetupCollectionFactory,
        StoreManagerInterface $storeManager,
        UrlInterface $urlBuilder,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $freeDeliverySetupCollectionFactory->create();
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
        foreach ($items as $freeDeliverySetup) {
            $freeDeliverySetupData = $freeDeliverySetup->getData();
            $freeDeliverySetupData['delivery_type'] = $freeDeliverySetupData['delivery_type'];
            $freeDeliverySetupData['day'] = $freeDeliverySetupData['day'];

            $this->loadedData[$freeDeliverySetup->getId()] = $freeDeliverySetupData;
        }
        return $this->loadedData;
    }

}
