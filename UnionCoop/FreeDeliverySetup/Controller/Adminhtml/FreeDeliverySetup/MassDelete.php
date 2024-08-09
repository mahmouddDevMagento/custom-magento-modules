<?php

namespace Unioncoop\FreeDeliverySetup\Controller\Adminhtml\FreeDeliverySetup;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Unioncoop\FreeDeliverySetup\Model\ResourceModel\FreeDeliverySetup\CollectionFactory;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action
{
    protected $filter;
    protected $collectionFactory;

    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        Filter $filter
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->filter = $filter;
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $selectedIds = $collection->getColumnValues('id');

        foreach ($collection->addFieldToFilter('id', ['in' => $selectedIds]) as $item) {
            $item->delete();
        }

        $this->messageManager->addSuccessMessage(__('Total of %1 record(s) were deleted.', count($selectedIds)));
        $this->_redirect('*/*/index');
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Unioncoop_FreeDeliverySetup::FreeDeliverySetup');
    }
}
