<?php

namespace BraveBison\Mobiapi\Controller\Adminhtml\Carousel;

class ProductGridData extends \Magento\Backend\App\Action
{
    protected $resultLayoutFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
    ) {
        parent::__construct($context);
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * Execute Fucntion for Class ProductGridData
     *
     * @return jSon
     */
    public function execute()
    {
        $resultLayout = $this->resultLayoutFactory->create();
        $this->getResponse()->setBody(
            $resultLayout->getLayout()->createBlock(
                \BraveBison\Mobiapi\Block\Adminhtml\Carousel\Tab\Carouselproducts::class
            )->toHtml()
        );
    }
}
