<?php
namespace BraveBison\Mobiapi\Model\Carousel;

use BraveBison\Mobiapi\Model\ResourceModel\Carousel\CollectionFactory;
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
        CollectionFactory $carouselCollectionFactory,
        StoreManagerInterface $storeManager,
        UrlInterface $urlBuilder,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $carouselCollectionFactory->create();
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
        foreach ($items as $carousel) {
            $carouselData = $carousel->getData();
            $image = $carouselData['image'];
            if ($image && is_string($image)) {
                $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
                $imageUrl = $mediaUrl .'wysiwyg/mobiapi/'. $image;
                $carouselData['image'] = [
                    0 => [
                        'name' => basename($image),
                        'url' => $imageUrl,
                    ]
                ];
            }

//            if(isset($carouselData['product_ids'])) {
//                $carouselData['product_ids'] = explode(',', $carouselData['product_ids']);
//            }
//
//            if(isset($carouselData['image_ids'])) {
//                $carouselData['image_ids'] = explode(',', $carouselData['image_ids']);
//            }
            $this->loadedData[$carousel->getId()] = $carouselData;
        }
        return $this->loadedData;
    }
}
