<?php

namespace BraveBison\Mobiapi\Block\Adminhtml\Carousel\Tab;

class Products extends \Magento\Backend\Block\Template
{
    /**
     * $_request
     */
    protected $request;

    /**
     * $carouselRepository
     */
    protected $carouselRepository;

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Backend\Block\Template\Context $context,
        \BraveBison\Mobiapi\Api\CarouselRepositoryInterface $carouselRepository,
        array $data = []
    ) {
        $this->request = $request;
        $this->carouselRepository = $carouselRepository;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        $this->setTemplate("carousel/products.phtml");
    }

    public function getCarouselProductsJson()
    {
        $carouselImages = "";
        $carouselId = $this->request->getParam("id");
        $carousel = $this->carouselRepository->getById($carouselId);
        $carouselProducts = $carousel->getProductIds();
        return $carouselProducts;
    }
}
