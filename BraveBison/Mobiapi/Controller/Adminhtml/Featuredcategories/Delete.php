<?php
namespace BraveBison\Mobiapi\Controller\Adminhtml\Featuredcategories;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use BraveBison\Mobiapi\Model\FeaturedcategoriesFactory;

class Delete extends Action
{
    protected $featuredcategoriesimageFactory;

    public function __construct(
        Context $context,
        FeaturedcategoriesFactory $featuredcategoriesimageFactory
    ) {
        parent::__construct($context);
        $this->featuredcategoriesimageFactory = $featuredcategoriesimageFactory;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if ($id) {
            try {
                $featuredCat = $this->featuredcategoriesimageFactory->create();
                $featuredCat->load($id);

                if (!$featuredCat->getId()) {
                    throw new \Exception(__('This Featured Category no longer exists.'));
                }

                $featuredCat->delete();
                $this->messageManager->addSuccessMessage(__('You deleted the Featured Category.'));
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
        return $this->_authorization->isAllowed('BraveBison_Mobiapi::featuredcategories');
    }
}
