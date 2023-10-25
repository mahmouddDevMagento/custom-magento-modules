<?php
namespace BraveBison\Mobiapi\Model\Walkthrough;

use BraveBison\Mobiapi\Model\ResourceModel\Walkthrough\CollectionFactory;
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
        CollectionFactory $walkthroughCollectionFactory,
        StoreManagerInterface $storeManager,
        UrlInterface $urlBuilder,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $walkthroughCollectionFactory->create();
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
        foreach ($items as $walkthrough) {
            $walkthroughData = $walkthrough->getData();
            $image = $walkthroughData['image'];
            if ($image && is_string($image)) {
                $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
                $imageUrl = $mediaUrl .'wysiwyg/mobiapi/'. $image;
                $walkthroughData['image'] = [
                    0 => [
                        'name' => basename($image),
                        'url' => $imageUrl,
                    ]
                ];
            }

            $this->loadedData[$walkthrough->getId()] = $walkthroughData;
        }
        return $this->loadedData;
    }
}
