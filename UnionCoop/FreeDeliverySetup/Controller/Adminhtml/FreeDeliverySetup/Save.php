<?php
namespace Unioncoop\FreeDeliverySetup\Controller\Adminhtml\FreeDeliverySetup;

use Magento\Backend\App\Action;
use Magento\Backend\Model\Auth\Session as AuthSession;
use Magento\Backend\Model\Session;
use Unioncoop\FreeDeliverySetup\Model\FreeDeliverySetup;
use Magento\User\Model\UserFactory;

class Save extends \Magento\Backend\App\Action
{
    /*
     * @var FreeDeliverySetup
     */
    protected $freeDeliverySetup;

    /**
     * @var Session
     */
    protected $adminsession;

    /**
     * @var AuthSession
     */
    protected $authSession;

    /**
     * @var UserFactory
     */
    protected $userFactory;

    /**
     * @param Action\Context $context
     * @param FreeDeliverySetup    $freeDeliverySetup
     * @param Session        $adminsession
     * @param AuthSession   $authSession
     * @param UserFactory   $userFactory
     */
    public function __construct(
        Action\Context $context,
        FreeDeliverySetup $freeDeliverySetup,
        Session $adminsession,
        AuthSession $authSession,
        UserFactory $userFactory,
        \Unioncoop\FreeDeliverySetup\Model\ResourceModel\FreeDeliverySetup\CollectionFactory $freeDeliverySetupCollection
    ) {
        parent::__construct($context);
        $this->freeDeliverySetup = $freeDeliverySetup;
        $this->adminsession = $adminsession;
        $this->authSession = $authSession;
        $this->userFactory = $userFactory;
        $this->freeDeliverySetupCollection = $freeDeliverySetupCollection;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            try {
                $deliverySetupId = isset($data['id']) ? $data['id'] : null;

                if ($deliverySetupId) {
                    // Edit existing delivery setup
                    $deliverySetup = $this->freeDeliverySetup->load($deliverySetupId);
                    if (!$deliverySetup->getId()) {
                        $this->messageManager->addErrorMessage(__('This delivery setup no longer exists.'));
                        return $resultRedirect->setPath('*/*/index');
                    }
                } else {
                    // Create new delivery setup
                    $deliverySetup = $this->freeDeliverySetup;
                }

                $freeDeliverySetupExistData = $this->freeDeliverySetupCollection->create()->addFieldToFilter('delivery_type', $data['delivery_type'])->addFieldToFilter('day', $data['day']);
                if (($deliverySetup->getDeliveryType() != $data['delivery_type'] || $deliverySetup->getDay() != $data['day']) && $freeDeliverySetupExistData->getSize() >= 1) {
                    $this->messageManager->addErrorMessage(__('Delivery type for this day is already exist'));
                    return $resultRedirect->setPath('*/*/');
                }

                // Set specific fields from $data to the model
                $deliverySetup->setDeliveryType($data['delivery_type']);
                $deliverySetup->setDay($data['day']);
                $deliverySetup->setAmount($data['amount']);

                // Set the admin user who made the change
                $adminUser = $this->authSession->getUser();

                if ($adminUser) {
                    $deliverySetup->setUpdatedBy($adminUser->getUsername());
                }

                // Save the model
                $deliverySetup->save();

                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->adminsession->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return $resultRedirect->setPath('*/*/addNew');
                    }
                }
                // Redirect to grid after successful creation/edit
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/addNew');
        }

        return $resultRedirect->setPath('*/*/addNew');
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Unioncoop_FreeDeliverySetup::FreeDeliverySetup');
    }

}
