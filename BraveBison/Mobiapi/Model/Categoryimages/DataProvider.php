<?php
namespace BraveBison\Mobiapi\Model\Categoryimages;

use BraveBison\Mobiapi\Model\ResourceModel\Categoryimages\CollectionFactory;
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
        CollectionFactory $categoryImagesCollectionFactory,
        StoreManagerInterface $storeManager,
        UrlInterface $urlBuilder,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $categoryImagesCollectionFactory->create();
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
        foreach ($items as $categoryImage) {
            $categoryImageData = $categoryImage->getData();
//var_dump($categoryImageData);die('dd');
            // Process icon image URL
            $iconImage = $categoryImageData['icon'];
            if ($iconImage && is_string($iconImage)) {
                $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
                $iconImageUrl = $mediaUrl . $iconImage;
                $categoryImageData['icon'] = [
                    0 => [
                        'name' => basename($iconImage),
                        'url' => $iconImageUrl,
                    ]
                ];
            }

            // Process banner image URL
            $bannerImage = $categoryImageData['banner'];
            if ($bannerImage && is_string($bannerImage)) {
                $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
                $bannerImageUrl = $mediaUrl . $bannerImage;
                $categoryImageData['banner'] = [
                    0 => [
                        'name' => basename($bannerImage),
                        'url' => $bannerImageUrl,
                    ]
                ];
            }

            // Process small banner image URL
            $smallBannerImage = $categoryImageData['smallbanner'];
            if ($smallBannerImage && is_string($smallBannerImage)) {
                $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
                $smallBannerImageUrl = $mediaUrl . $smallBannerImage;
                $categoryImageData['smallbanner'] = [
                    0 => [
                        'name' => basename($smallBannerImage),
                        'url' => $smallBannerImageUrl,
                    ]
                ];
            }

            $this->loadedData[$categoryImage->getId()] = $categoryImageData;
        }
        return $this->loadedData;
    }

//    public function getData()
//    {
//        if (isset($this->loadedData)) {
//            return $this->loadedData;
//        }
//        $items = $this->collection->getItems();
//        foreach ($items as $categoryImage) {
//            $categoryImageData = $categoryImage->getData();
//
//            // Process icon image URL
//            $iconImage = $categoryImageData['icon'];
//            if ($iconImage && is_string($iconImage)) {
//                $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
//                $iconImageUrl = $mediaUrl . $iconImage;
//                $categoryImageData['icon'] = [
//                    0 => [
//                        'name' => basename($iconImage),
//                        'url' => $iconImageUrl,
//                    ]
//                ];
//            }
//
//            // Process banner images URLs
//            $bannerImages = $categoryImageData['banner'];
//            if ($bannerImages) {
//                $bannerImagesArray = explode(',', $bannerImages);
//                $bannerImagesData = [];
//                foreach ($bannerImagesArray as $bannerImage) {
//                    $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
//                    $bannerImageUrl = $mediaUrl . $bannerImage;
//                    $bannerImagesData[] = [
//                        'name' => basename($bannerImage),
//                        'url' => $bannerImageUrl,
//                    ];
//                }
//                $categoryImageData['banner'] = $bannerImagesData;
//            } else {
//                $categoryImageData['banner'] = []; // Set to empty array if no images
//            }
//
//            // Process small banner images URLs
//            $smallBannerImages = $categoryImageData['smallbanner'];
//            if ($smallBannerImages) {
//                $smallBannerImagesArray = explode(',', $smallBannerImages);
//                $smallBannerImagesData = [];
//                foreach ($smallBannerImagesArray as $smallBannerImage) {
//                    $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
//                    $smallBannerImageUrl = $mediaUrl . $smallBannerImage;
//                    $smallBannerImagesData[] = [
//                        'name' => basename($smallBannerImage),
//                        'url' => $smallBannerImageUrl,
//                    ];
//                }
//                $categoryImageData['smallbanner'] = $smallBannerImagesData;
//            } else {
//                $categoryImageData['smallbanner'] = []; // Set to empty array if no images
//            }
//
//            $this->loadedData[$categoryImage->getId()] = $categoryImageData;
//        }
//        return $this->loadedData;
//    }
}
