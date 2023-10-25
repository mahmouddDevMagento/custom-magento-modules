<?php

namespace BestResponseMedia\MultishippingCustomization\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;

class AddressForm extends Action
{
    protected $_resultPageFactory;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory
    )
    {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->_resultJsonFactory->create();
        $resultPage = $this->_resultPageFactory->create();
        if ($this->getRequest()->isAjax()) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $block = $resultPage->getLayout()
                ->createBlock('Magento\Customer\Block\Address\Edit',
            "customer_address_edit_cusotm",
                    [
                        'data' => [
                            'attribute_data' => $objectManager->create('Magento\Customer\Block\DataProviders\AddressAttributeData'),
                            'view_model' => $objectManager->create('Magento\Customer\ViewModel\Address'),
                            'post_code_config'=>$objectManager->create('Magento\Customer\Block\DataProviders\PostCodesPatternsAttributeData'),
                            'region_provider'=>$objectManager->create('Magento\Customer\ViewModel\Address\RegionProvider')
                        ]
                    ]
                )
                ->setTemplate('Magento_Customer::address/edit.phtml')
                ->toHtml();

            $result->setData(['output' => $block]);
            return $result;
        }
    }
}
