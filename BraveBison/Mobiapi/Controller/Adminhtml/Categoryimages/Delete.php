<?php
namespace BraveBison\Mobiapi\Controller\Adminhtml\Categoryimages;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use BraveBison\Mobiapi\Model\CategoryimagesFactory;

class Delete extends Action
{
    protected $categoryimagesFactory;

    public function __construct(
        Context $context,
        CategoryimagesFactory $categoryimagesFactory
    ) {
        parent::__construct($context);
        $this->categoryimagesFactory = $categoryimagesFactory;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if ($id) {
            try {
                $model = $this->categoryimagesFactory->create();
                $model->load($id);

                if (!$model->getId()) {
                    throw new \Exception(__('This Category Images no longer exists.'));
                }

                $model->delete();
                $this->messageManager->addSuccessMessage(__('You deleted the Category Images.'));
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
        return $this->_authorization->isAllowed('BraveBison_Mobiapi::categoryimages');
    }
}
