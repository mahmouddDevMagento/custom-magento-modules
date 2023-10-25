<?php
namespace BraveBison\Mobiapi\Model\Featuredcategories;

use BraveBison\Mobiapi\Model\ResourceModel\Featuredcategories\CollectionFactory;
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
        CollectionFactory $FeaturedcategoriesCollectionFactory,
        StoreManagerInterface $storeManager,
        UrlInterface $urlBuilder,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $FeaturedcategoriesCollectionFactory->create();
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
        foreach ($items as $featuredCategorie) {
            $featuredCategorieData = $featuredCategorie->getData();
            $image = $featuredCategorieData['image'];
            if ($image && is_string($image)) {
                $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
                $imageUrl = $mediaUrl .'wysiwyg/mobiapi/'. $image;
                $featuredCategorieData['image'] = [
                    0 => [
                        'name' => basename($image),
                        'url' => $imageUrl,
                    ]
                ];
            }
            $this->loadedData[$featuredCategorie->getId()] = $featuredCategorieData;
        }
        return $this->loadedData;
    }
}
