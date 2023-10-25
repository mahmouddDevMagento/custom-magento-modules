<?php
namespace BraveBison\Mobiapi\Controller\Adminhtml\Carousel;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use BraveBison\Mobiapi\Model\CarouselFactory;

class Delete extends Action
{
    protected $carouselFactory;

    public function __construct(
        Context $context,
        CarouselFactory $carouselFactory
    ) {
        parent::__construct($context);
        $this->carouselimageFactory = $carouselFactory;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if ($id) {
            try {
                $model = $this->carouselimageFactory->create();
                $model->load($id);

                if (!$model->getId()) {
                    throw new \Exception(__('This Carousel no longer exists.'));
                }

                $model->delete();
                $this->messageManager->addSuccessMessage(__('You deleted the Carousel.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*/index');
        return $resultRedirect;
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('BraveBison_Mobiapi::carousel');
    }
}
