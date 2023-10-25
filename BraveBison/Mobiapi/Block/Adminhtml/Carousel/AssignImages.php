<?php

namespace BraveBison\Mobiapi\Block\Adminhtml\Carousel;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use BraveBison\Mobiapi\Model\ResourceModel\Carouselimage\CollectionFactory as CarouselimageCollectionFactory;

class AssignImages extends Template
{
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'images/assign_images.phtml';

    /**
     * @var \BraveBison\Mobiapi\Block\Adminhtml\Carousel\Tab\Imagegrid
     */
    protected $blockGrid;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * @var CarouselimageCollectionFactory
     */
    protected $carouselimageCollectionFactory;

    public function __construct(
        Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        CarouselimageCollectionFactory $carouselimageCollectionFactory,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->jsonEncoder = $jsonEncoder;
        $this->carouselimageCollectionFactory = $carouselimageCollectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve instance of grid block
     *
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBlockGrid()
    {
        if (null === $this->blockGrid) {
            $this->blockGrid = $this->getLayout()->createBlock(
                'BraveBison\Mobiapi\Block\Adminhtml\Carousel\Tab\Imagegrid',
                'carousel.image.grid'
            );
        }
        return $this->blockGrid;
    }

    /**
     * Return HTML of grid block
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getBlockGrid()->toHtml();
    }

    /**
     * @return string
     */
    public function getImagesJson()
    {
        $carouselId = $this->getRequest()->getParam('id'); // Replace with your actual parameter name
        $imageCollection = $this->carouselimageCollectionFactory->create();
//        $imageCollection->addFieldToSelect(['id', 'position']);
        $imageCollection->addFieldToFilter('id', ['eq' => $carouselId]); // Replace with your actual field name
        $result = [];
        if (!empty($imageCollection->getData())) {
            foreach ($imageCollection->getData() as $images) {
                $result[$images['id']] = '';
            }
            return $this->jsonEncoder->encode($result);
        }
        return '{}';
    }

    public function getItem()
    {
        return $this->registry->registry('carousel_image'); // Replace with your actual registry name
    }
}
