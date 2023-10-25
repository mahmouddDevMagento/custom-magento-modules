<?php

namespace BestResponseMedia\MultishippingCustomization\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Multishipping\Controller\Checkout;
use Magento\Multishipping\Model\Checkout\Type\Multishipping\State;

class CustomShipping extends Checkout implements HttpPostActionInterface
{

    public function execute()
    {

        if ($this->getRequest()->isAjax()) {
            $shippingMethods = $this->getRequest()->getPost('shipping_method');
            try {
                $this->_eventManager->dispatch(
                    'checkout_controller_multishipping_shipping_post',
                    ['request' => $this->getRequest(), 'quote' => $this->_getCheckout()->getQuote()]
                );
                $this->_getCheckout()->setShippingMethods($shippingMethods);
                $this->_getState()->setActiveStep(State::STEP_BILLING);
                $this->_getState()->setCompleteStep(State::STEP_SHIPPING);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }
    }
}
