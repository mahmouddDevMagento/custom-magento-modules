<?php
namespace BraveBison\Mobiapi\Controller\Adminhtml\Walkthrough;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use BraveBison\Mobiapi\Model\WalkthroughFactory;

class Delete extends Action
{
    protected $walkthroughFactory;

    public function __construct(
        Context $context,
        WalkthroughFactory $walkthroughFactory
    ) {
        parent::__construct($context);
        $this->walkthroughFactory = $walkthroughFactory;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if ($id) {
            try {
                $featuredCat = $this->walkthroughFactory->create();
                $featuredCat->load($id);

                if (!$featuredCat->getId()) {
                    throw new \Exception(__('This Walkthrough no longer exists.'));
                }

                $featuredCat->delete();
                $this->messageManager->addSuccessMessage(__('You deleted the Walkthrough.'));
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
        return $this->_authorization->isAllowed('BraveBison_Mobiapi::walkthrough');
    }
}
