<?php

namespace BraveBison\Mobiapi\Controller\Adminhtml\Homepage;


use Magento\Framework\App\Filesystem\DirectoryList;

class Index extends \Magento\Backend\App\Action
{
    protected $filter;
    protected $storeManager;
    protected $coreRegistry;
    protected $mediaDirectory;
    protected $resultJsonFactory;
    protected $collectionFactory;
    protected $resultPageFactory;
    protected $carouselRepository;
    protected $fileUploaderFactory;
    protected $carouselDataFactory;
    protected $resultForwardFactory;

    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \BraveBison\Mobiapi\Api\CarouselRepositoryInterface $carouselRepository,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \BraveBison\Mobiapi\Api\Data\CarouselInterfaceFactory $carouselDataFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \BraveBison\Mobiapi\Model\ResourceModel\Carousel\CollectionFactory $collectionFactory
    ) {
        $this->filter               = $filter;
        $this->coreRegistry         = $coreRegistry;
        $this->storeManager         = $storeManager;
        $this->mediaDirectory       = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->collectionFactory    = $collectionFactory;
        $this->resultPageFactory    = $resultPageFactory;
        $this->resultJsonFactory    = $resultJsonFactory;
        $this->carouselRepository   = $carouselRepository;
        $this->fileUploaderFactory  = $fileUploaderFactory;
        $this->carouselDataFactory  = $carouselDataFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu("BraveBison_Mobiapi::homepage");
        $resultPage->getConfig()->getTitle()->prepend(__("HomePage Configuration"));
        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed("BraveBison_Mobiapi::homepage");
    }
}
