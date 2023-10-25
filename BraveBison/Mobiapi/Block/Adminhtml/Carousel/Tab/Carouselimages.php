<?php

namespace BraveBison\Mobiapi\Block\Adminhtml\Carousel\Tab;

use Magento\Backend\Block\Template\Context;

/**
 * Class Carouselimages block
 */
class Carouselimages extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $imagesCollection;

    public function __construct(
        Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \BraveBison\Mobiapi\Model\ResourceModel\Carouselimage\CollectionFactory $imagesCollection,
        array $data = []
    ) {
        $this->imagesCollection = $imagesCollection;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setUseAjax(true);
        $this->setDefaultDir("ASC");
        $this->setDefaultSort("id");
        $this->setId("carousel_images");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = $this->imagesCollection->create();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            "triggers",
            [
                "type" => "checkbox",
                "align" => "center",
                "index" => "id"
            ]
        );

        $this->addColumn(
            "id",
            [
                "type" => "number",
                "align" => "center",
                "width" => "30px",
                "index" => "id",
                "header" => __("ID")
            ]
        );

        $this->addColumn(
            "filename",
            [
                "type" => "image",
                "align" => "center",
                "index" => "filename",
                "header" => __("Thumbnail"),
                "escape" => true,
                "filter" => false,
                "renderer" => \BraveBison\Mobiapi\Block\Adminhtml\Thumbnail::class,
                "sortable" => false
            ]
        );

        $this->addColumn(
            "title",
            [
                "index" => "title",
                "align" => "left",
                "header" => __("Title")
            ]
        );

        $this->addColumn(
            "status",
            [
                "header" => __("Status"),
                "index" => "status",
                "type" => "options",
                "options" => [1=>__("Enabled"), 0=>__("Disabled")]
            ]
        );
        return parent::_prepareColumns();
    }

    /**
     * Function to get Grid Url
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl("*/*/imageGridData", ["_current"=>true]);
    }
}
