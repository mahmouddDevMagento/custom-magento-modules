<?php
namespace BraveBison\Mobiapi\Model\Carouselimage;

use BraveBison\Mobiapi\Model\ResourceModel\Carouselimage\CollectionFactory;
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
        CollectionFactory $carouselimageCollectionFactory,
        StoreManagerInterface $storeManager,
        UrlInterface $urlBuilder,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $carouselimageCollectionFactory->create();
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
        foreach ($items as $carouselimage) {
            $carouselImageData = $carouselimage->getData();
            $image = $carouselImageData['image'];
            if ($image && is_string($image)) {
                $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
                $imageUrl = $mediaUrl .'wysiwyg/mobiapi/'. $image;
                $carouselImageData['image'] = [
                    0 => [
                        'name' => basename($image),
                        'url' => $imageUrl,
                    ]
                ];
            }
            $this->loadedData[$carouselimage->getId()] = $carouselImageData;
        }
        return $this->loadedData;
    }
}
