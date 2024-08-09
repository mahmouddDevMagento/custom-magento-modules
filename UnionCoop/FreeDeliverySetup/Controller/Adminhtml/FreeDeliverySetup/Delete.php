<?php
namespace Unioncoop\FreeDeliverySetup\Controller\Adminhtml\FreeDeliverySetup;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Unioncoop\FreeDeliverySetup\Model\FreeDeliverySetupFactory;

class Delete extends Action
{
    protected $freeDeliverySetupFactory;

    public function __construct(
        Context $context,
        FreeDeliverySetupFactory $freeDeliverySetupFactory
    ) {
        parent::__construct($context);
        $this->freeDeliverySetupFactory = $freeDeliverySetupFactory;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if ($id) {
            try {
                $model = $this->freeDeliverySetupFactory->create();
                $model->load($id);

                if (!$model->getId()) {
                    throw new \Exception(__('This delivery setup no longer exists.'));
                }

                $model->delete();
                $this->messageManager->addSuccessMessage(__('You deleted the delivery setup.'));
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
        return $this->_authorization->isAllowed('Unioncoop_FreeDeliverySetup::FreeDeliverySetup');
    }
}
