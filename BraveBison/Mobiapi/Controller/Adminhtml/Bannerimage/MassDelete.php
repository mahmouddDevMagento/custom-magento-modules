<?php
namespace BraveBison\Mobiapi\Controller\Adminhtml\Bannerimage;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use BraveBison\Mobiapi\Model\ResourceModel\Bannerimage\CollectionFactory;

class MassDelete extends Action
{
    protected $collectionFactory;

    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
    }

    public function execute()
    {
        $collection = $this->collectionFactory->create();
        $selectedIds = $this->getRequest()->getParam('selected');

        foreach ($collection->addFieldToFilter('id', ['in' => $selectedIds]) as $item) {
            $item->delete(); 
        }

        $this->messageManager->addSuccessMessage(__('Total of %1 record(s) were deleted.', count($selectedIds)));
        $this->_redirect('*/*/index');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed("BraveBison_Mobiapi::bannerimage");
    }
}
