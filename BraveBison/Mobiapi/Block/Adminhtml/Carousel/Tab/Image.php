<?php

namespace BraveBison\Mobiapi\Block\Adminhtml\Carousel\Tab;

/**
 * Class Image block
 */
class Image extends \Magento\Backend\Block\Template
{
    protected $request;
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
        $this->setTemplate("carousel/images.phtml");
    }

    public function getCarouselImagesJson()
    {
        $carouselImages = "";
        $carouselId = $this->request->getParam("id");
        $carousel = $this->carouselRepository->getById($carouselId);
        $carouselImages = $carousel->getImageIds();
        return $carouselImages;
    }
}
