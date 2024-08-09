<?php

namespace Unioncoop\TamayazRedemption\Block\Adminhtml\RedemptionFactor\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Unioncoop\TamayazRedemption\Block\Adminhtml\Button\Generic;

class Save extends Generic implements ButtonProviderInterface
{

    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save Redemption Factor'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'promotion_form.promotion_form',
                                'actionName' => 'save',
                                'params' => [
                                    true,
                                    [
                                        'back' => 'continue'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
