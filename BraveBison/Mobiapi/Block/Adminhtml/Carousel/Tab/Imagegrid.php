<?php

namespace BraveBison\Mobiapi\Block\Adminhtml\Carousel\Tab;

use Magento\Backend\Block\Widget\Grid;
use BraveBison\Mobiapi\Model\ResourceModel\Carouselimage\CollectionFactory;

class Imagegrid extends Grid
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Registry $coreRegistry,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->collectionFactory = $collectionFactory;

        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('carousel_image_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
    }

    /**
     * Prepare the grid collection
     *
     * @return $this
     */
    protected function _prepareCollection()
    {
        // Replace the collection instantiation with your actual collection
        $collection = $this->collectionFactory->create();

//        $collection = $this->_coreRegistry->registry('carousel_image_grid');
        $this->setCollection($collection);

        parent::_prepareCollection();

        return $this;
    }

    /**
     * Prepare grid columns
     *
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            [
                'header' => __('ID'),
                'index' => 'id',
                'type' => 'number',
            ]
        );

//        $this->addColumn(
//            'image',
//            [
//                'header' => __('Image'),
//                'index' => 'image',
//                'renderer' => 'BraveBison\Mobiapi\Block\Adminhtml\Carousel\Tab\Renderer\Image',
//            ]
//        );

        $this->addColumn(
            'title',
            [
                'header' => __('Title'),
                'index' => 'title',
            ]
        );

        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'type' => 'options',
                'options' => [0 => __('Disabled'), 1 => __('Enabled')],
            ]
        );


        return parent::_prepareColumns();
    }

    /**
     * Retrieve selected images JSON
     *
     * @return string
     */
    public function getSelectedImagesJson()
    {
        $images = $this->_coreRegistry->registry('selected_images');
        if (!empty($images)) {
            return json_encode($images);
        }
        return '{}';
    }


    public function getGridUrl()
    {
        return $this->getUrl('*/index/grids', ['_current' => true]);
    }
}
