<?php

namespace Unioncoop\FreeDeliverySetup\Block\Adminhtml\FreeDeliverySetup\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Unioncoop\FreeDeliverySetup\Block\Adminhtml\Button\Generic;

class Back extends Generic implements ButtonProviderInterface
{

    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 10
        ];
    }

    /**
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/*/index');
    }
}
