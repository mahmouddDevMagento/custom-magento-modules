<?php

namespace Unioncoop\TamayazRedemption\Controller\Adminhtml\RedemptionFactor;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Unioncoop\TamayazRedemption\Model\ResourceModel\RedemptionFactor\CollectionFactory;
use Magento\Ui\Component\MassAction\Filter;
use Unioncoop\TamayazRedemption\Api\RedemptionFactorRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;

class MassDelete extends Action
{
    protected $filter;
    protected $collectionFactory;
    protected $redemptionFactorRepository;

    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        Filter $filter,
        RedemptionFactorRepositoryInterface $redemptionFactorRepository
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->filter = $filter;
        $this->redemptionFactorRepository = $redemptionFactorRepository;
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $selectedIds = $collection->getColumnValues('id');
        $deletedCount = 0;

        foreach ($collection->addFieldToFilter('id', ['in' => $selectedIds]) as $item) {
            try {
                $this->redemptionFactorRepository->deleteById($item->getId());
                $deletedCount++;
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('An error occurred while deleting the redemption factor.'));
            }
        }

        if ($deletedCount) {
            $this->messageManager->addSuccessMessage(__('Total of %1 record(s) were deleted.', $deletedCount));
        }

        $this->_redirect('*/*/index');
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Unioncoop_TamayazRedemption::redemption_factor');
    }
}
