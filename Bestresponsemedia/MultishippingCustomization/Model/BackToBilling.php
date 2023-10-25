<?php

namespace BestResponseMedia\MultishippingCustomization\Model;


use Magento\Multishipping\Model\Checkout\Type\Multishipping\State;

class BackToBilling extends \Magento\Multishipping\Controller\Checkout\BackToBilling
{
    /**
     * Back to billing action
     *
     * @return void
     */
    public function execute()
    {
        $this->_getState()->setActiveStep(State::STEP_BILLING);
        $this->_getState()->unsCompleteStep(State::STEP_OVERVIEW);
        $this->_redirect('*/*/shipping');
    }
}
