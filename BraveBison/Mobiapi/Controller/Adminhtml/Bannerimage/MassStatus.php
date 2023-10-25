<?php
namespace BraveBison\Mobiapi\Controller\Adminhtml\Bannerimage;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use BraveBison\Mobiapi\Model\ResourceModel\Bannerimage\CollectionFactory;

class MassStatus extends \Magento\Backend\App\Action
{
    protected $filter;
    protected $collectionFactory;

    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    public function execute()
    {
//        $ids = $this->getRequest()->getParam('status');
//        var_dump($ids);die('ids');
        $statusValue = $this->getRequest()->getParam('status');

        $collection = $this->filter->getCollection($this->collectionFactory->create());
//        var_dump($collection);die('deee');

        foreach ($collection as $item) {


//            if ($item->getStatus() == 1) {
                $item->setStatus($statusValue);
                $item->save();
//            }
        }

        $updatedCount = $collection->count();

            $this->messageManager->addSuccess(__('A total of %1 record(s) have been disabled.', $collection->getSize()));


        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed("BraveBison_Mobiapi::bannerimage");
    }
}
