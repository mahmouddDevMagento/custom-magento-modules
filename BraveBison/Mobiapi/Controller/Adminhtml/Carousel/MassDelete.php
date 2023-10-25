<?php

namespace BraveBison\Mobiapi\Controller\Adminhtml\Carousel;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use BraveBison\Mobiapi\Model\ResourceModel\Carousel\CollectionFactory;

class MassDelete extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    protected $filter;
    protected $collectionFactory;

    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory)
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $deletedCount = 0;

        foreach ($collection as $item) {
            $item->delete();
            $deletedCount++;
        }

        $this->messageManager->addSuccessMessage(
            __('A total of %1 record(s) have been deleted.', $deletedCount)
        );

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed("BraveBison_Mobiapi::carousel");
    }
}
