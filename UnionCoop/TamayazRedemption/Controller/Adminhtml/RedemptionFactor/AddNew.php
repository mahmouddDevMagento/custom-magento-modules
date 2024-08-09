<?php
namespace Unioncoop\TamayazRedemption\Controller\Adminhtml\RedemptionFactor;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class AddNew extends Action
{
    protected $resultPageFactory;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Add New Redemption Factor'));
        return $resultPage;
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Unioncoop_TamayazRedemption::redemption_factor');
    }

}

