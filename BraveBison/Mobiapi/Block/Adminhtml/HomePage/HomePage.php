<?php

namespace BraveBison\Mobiapi\Block\Adminhtml\HomePage;

class HomePage extends \Magento\Backend\Block\Template
{
    protected $coreRegistry;
    protected $appcreatorFactory;
    protected $store;
    protected $bannerImage;
    protected $carouselFactory;
    protected $carouselImageFactory;
    protected $featuredCategories;
    protected $date;
    protected $localeDate;
    protected $productStatus;
    protected $storeInterface;
    protected $productCollection;
    protected $categoryResourceModel;
    protected $productVisibility;
    protected $productResourceModel;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \BraveBison\Mobiapi\Model\AppcreatorFactory $appcreatorFactory,
        \Magento\Store\Model\Store $store,
        \BraveBison\Mobiapi\Model\Bannerimage $bannerImage,
        \BraveBison\Mobiapi\Model\CarouselFactory $carouselFactory,
        \BraveBison\Mobiapi\Model\CarouselimageFactory $carouselImageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeInterface,
        \BraveBison\Mobiapi\Model\Featuredcategories $featuredCategories,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollection,
        \Magento\Framework\Filesystem\Driver\File $fileDriver,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Catalog\Model\ResourceModel\Category $categoryResourceModel,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Catalog\Model\ResourceModel\Product $productResourceModel,

        array $data = []
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->appcreatorFactory = $appcreatorFactory;
        $this->date = $date;
        $this->store = $store;
        $this->localeDate = $localeDate;
        $this->productStatus = $productStatus;
        $this->bannerImage = $bannerImage;
        $this->storeInterface = $storeInterface;
        $this->carouselFactory = $carouselFactory;
        $this->productCollection = $productCollection;
        $this->fileDriver = $fileDriver;
        $this->featuredCategories = $featuredCategories;
        $this->carouselImageFactory = $carouselImageFactory;
        $this->categoryResourceModel = $categoryResourceModel;
        $this->productVisibility = $productVisibility;
        $this->productResourceModel = $productResourceModel;
        $this->result = [];
        parent::__construct($context, $data);
    }

    public function getFeaturedCategories()
    {
//        return $this->featuredCategories
//            ->getCollection()
//            ->addFieldToFilter("status", 1);

         $featuredCategoryCollection = $this->featuredCategories
            ->getCollection()
            ->addFieldToFilter("status", 1)
             ->addFieldToFilter([
                 'store_id',
                 'store_id'
             ], [
                 ["finset" => 0],
                 ["finset" => 1]
             ])
            ->setOrder("sort_order", "ASC");

        $featuredCategories = [];
        foreach ($featuredCategoryCollection as $category) {
            $returnedCategory = [];
            $returnedCategory["categoryId"] = $category->getCategoryId();
            $returnedCategory["categoryName"] = $this->categoryResourceModel->getAttributeRawValue(
                $category->getCategoryId(),
                "name",
                1
            );
            if ($category->getCategoryId()) {
                $featuredCategories[] = $returnedCategory;
            }
        }
       $this->result["featuredCategories"] = $featuredCategories;

    }

    public function getBannerImages()
    {
        $collection = $this->bannerImage
            ->getCollection()
            ->addFieldToFilter("status", 1)
            ->addFieldToFilter([
                'store_id',
                'store_id'
            ], [
                ["finset" => 0],
                ["finset" => 1]
            ])->setOrder("sort_order", "ASC");
        $bannerImages = [];
        foreach ($collection as $banner) {
            $returnedBanner = [];
            $returnedBanner["bannerType"] = $banner->getType();
            if ($banner->getType() == "category") {
                $categoryName = $this->categoryResourceModel->getAttributeRawValue(
                    $banner->getProductCatId(),
                    "name",
                    1
                );
                $returnedBanner["id"] = $banner->getProductCatId();
                $returnedBanner["name"] = $categoryName;
            } elseif ($banner->getType() == "product") {
                $productName = $this->productResourceModel->getAttributeRawValue(
                    $banner->getProductCatId(),
                    "name",
                    1
                );
                $returnedBanner["id"] = $banner->getProductCatId();
                $returnedBanner["name"] = $productName;
            }
            $bannerImages[] = $returnedBanner;
        }
         $this->result["bannerImages"] = $bannerImages;
    }

    public function getCarouselsData()
    {
        $collection = $this->carouselFactory->create()->getCollection()
            ->addFieldToFilter("status", 1)
            ->setOrder("sort_order", "ASC");


        foreach ($collection as $carousel) {
            if ($carousel->getType() == 'product') {
                $returnedCarousel = [];
                $returnedCarousel["id"] = $carousel->getId();
                $returnedCarousel["type"] = "product";
                $returnedCarousel["label"] = $carousel->getTitle();
                if ($carousel->getColorCode()) {
                    $returnedCarousel["color"] = $carousel->getColorCode();
                }

                $carouselProductstIds = explode(",", $carousel->getProductIds());

                $this->result["carousel"][] = $returnedCarousel;

            } else {
                $returnedCarousel = [];
                $returnedCarousel["id"] = $carousel->getId();
                $returnedCarousel["type"] = "image";
                $returnedCarousel["label"] = $carousel->getTitle();
                if ($carousel->getColorCode()) {
                    $returnedCarousel["color"] = $carousel->getColorCode();
                }
                $this->result["carousel"][] = $returnedCarousel;

            }
        }
//        return $this->result["carousel"];
//        die('qqqqqqqqqq');
    }

    public function getLayoutData()
    {
        $this->getFeaturedCategories();
        $this->getBannerImages();
//        $this->getCarouselsData();
//        return $this->result["bannerImages"];
        try {
            $arr = [];
            foreach ($this->result as $key => $data) {
                if ($key == 'featuredCategories') {
                    $arr[] = [
                        'id'=>'featuredcategories',
                        'type'=>'category',
                        'label'=>'Featured Categories'
                    ];
                }

                if ($key == 'bannerImages') {
                    $arr[] = [
                        'id'=>'bannerimage',
                        'type'=>'banner',
                        'label'=>'Banner Images'
                    ];
                }

            }
            return ['success' => true, 'data' => $arr];

        } catch (\Exception $e) {
            return ['success' => false, 'data'=> null];
        }
    }
    public function getSortedSelectedLayouts()
    {
        $selectedLayouts = $this->appcreatorFactory->create()
            ->getCollection()
            ->getData();

        $sortedLayouts = [];
        foreach ($selectedLayouts as $layout) {
            $positions = explode(',', $layout['position']);
            foreach ($positions as $position) {
                $sortedLayouts[] = [
                    'layout_id' => $layout['layout_id'],
                    'label' => $layout['label'],
                    'type' => $layout['type'],
                    'position' => $position,
                ];
            }
        }

        // Sort the layouts based on position
        usort($sortedLayouts, function ($a, $b) {
            return $a['position'] - $b['position'];
        });

        return $sortedLayouts;
    }

    public function getJsonSelectedLayouts()
    {
        $selectedLayouts = $this->getSortedSelectedLayouts();

        // Sort the layouts based on position
        usort($selectedLayouts, function ($a, $b) {
            return $a['position'] - $b['position'];
        });

        return json_encode($selectedLayouts);
    }

}
