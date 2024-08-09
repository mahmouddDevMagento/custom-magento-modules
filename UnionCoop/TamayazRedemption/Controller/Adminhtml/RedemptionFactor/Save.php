<?php
namespace Unioncoop\TamayazRedemption\Controller\Adminhtml\RedemptionFactor;

use Magento\Backend\App\Action;
use Magento\Backend\Model\Auth\Session as AuthSession;
use Magento\Backend\Model\Session;
use Unioncoop\TamayazRedemption\Api\RedemptionFactorRepositoryInterface;
use Unioncoop\TamayazRedemption\Model\RedemptionFactorFactory;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var RedemptionFactorRepositoryInterface
     */
    protected $redemptionFactorRepository;

    /**
     * @var RedemptionFactorFactory
     */
    protected $redemptionFactorFactory;

    /**
     * @var Session
     */
    protected $adminsession;

    /**
     * @var AuthSession
     */
    protected $authSession;

    /**
     * @param Action\Context $context
     * @param RedemptionFactorRepositoryInterface $redemptionFactorRepository
     * @param RedemptionFactorFactory $redemptionFactorFactory
     * @param Session $adminsession
     * @param AuthSession $authSession
     */
    public function __construct(
        Action\Context $context,
        RedemptionFactorRepositoryInterface $redemptionFactorRepository,
        RedemptionFactorFactory $redemptionFactorFactory,
        Session $adminsession,
        AuthSession $authSession,
    ) {
        parent::__construct($context);
        $this->redemptionFactorRepository = $redemptionFactorRepository;
        $this->redemptionFactorFactory = $redemptionFactorFactory;
        $this->adminsession = $adminsession;
        $this->authSession = $authSession;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            try {
                $redemptionFactorId = isset($data['id']) ? $data['id'] : null;

                if ($redemptionFactorId) {
                    // Edit existing redemption factor
                    $redemptionFactor = $this->redemptionFactorRepository->getById($redemptionFactorId);
                    if (!$redemptionFactor->getId()) {
                        $this->messageManager->addErrorMessage(__('This redemption factor no longer exists.'));
                        return $resultRedirect->setPath('*/*/index');
                    }
                } else {
                    // Create new redemption factor
                    $redemptionFactor = $this->redemptionFactorFactory->create();
                }

                // Set specific fields from $data to the model
                $redemptionFactor->setCode($data['code']);
                $redemptionFactor->setRedemptionFactor($data['redemption_factor']);

                // Set the admin user who made the change
                $adminUser = $this->authSession->getUser();

                if ($adminUser) {
                    $redemptionFactor->setUpdatedBy($adminUser->getUsername());
                }

                // Save the model
                $this->redemptionFactorRepository->save($redemptionFactor);

                $this->messageManager->addSuccess(__('The redemption factor has been saved.'));
                $this->adminsession->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return $resultRedirect->setPath('*/*/new');
                    }
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/new');
        }

        return $resultRedirect->setPath('*/*/new');
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Unioncoop_TamayazRedemption::redemption_factor');
    }
}
