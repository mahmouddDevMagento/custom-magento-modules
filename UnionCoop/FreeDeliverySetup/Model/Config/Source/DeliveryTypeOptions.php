<?php
namespace Unioncoop\FreeDeliverySetup\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class DeliveryTypeOptions implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'normal', 'label' => __('Normal')],
            ['value' => 'express', 'label' => __('Express')],
            ['value' => 'clickandcollect', 'label' => __('Click-Collect')]
        ];
    }
}
