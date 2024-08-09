<?php
namespace Unioncoop\TamayazRedemption\Controller\Adminhtml\RedemptionFactor;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Unioncoop\TamayazRedemption\Model\RedemptionFactorFactory;
use Unioncoop\TamayazRedemption\Api\RedemptionFactorRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

class Delete extends Action
{
    protected $redemptionFactorFactory;
    protected $redemptionFactorRepository;

    public function __construct(
        Context $context,
        RedemptionFactorFactory $redemptionFactorFactory,
        RedemptionFactorRepositoryInterface $redemptionFactorRepository
    ) {
        parent::__construct($context);
        $this->redemptionFactorFactory = $redemptionFactorFactory;
        $this->redemptionFactorRepository = $redemptionFactorRepository;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if ($id) {
            try {
                $this->redemptionFactorRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('You deleted the redemption factor.'));
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('This redemption factor no longer exists.'));
            } catch (CouldNotDeleteException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('An error occurred while trying to delete the redemption factor.'));
            }
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*/index');
        return $resultRedirect;
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Unioncoop_TamayazRedemption::redemption_factor');
    }
}
