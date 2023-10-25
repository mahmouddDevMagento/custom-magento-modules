<?php
namespace BraveBison\Mobiapi\Controller\Adminhtml\Bannerimage;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use BraveBison\Mobiapi\Model\BannerimageFactory;

class Delete extends Action
{
    protected $bannerimageFactory;

    public function __construct(
        Context $context,
        BannerimageFactory $bannerimageFactory
    ) {
        parent::__construct($context);
        $this->bannerimageFactory = $bannerimageFactory;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if ($id) {
            try {
                $model = $this->bannerimageFactory->create();
                $model->load($id);

                if (!$model->getId()) {
                    throw new \Exception(__('This banner no longer exists.'));
                }

                $model->delete();
                $this->messageManager->addSuccessMessage(__('You deleted the banner.'));
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
        return $this->_authorization->isAllowed('BraveBison_Mobiapi::bannerimage');
    }
}
