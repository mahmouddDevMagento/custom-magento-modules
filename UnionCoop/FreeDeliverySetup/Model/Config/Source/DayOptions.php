<?php

namespace Unioncoop\FreeDeliverySetup\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class DayOptions implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'monday', 'label' => __('Monday')],
            ['value' => 'tuesday', 'label' => __('Tuesday')],
            ['value' => 'wednesday', 'label' => __('Wednesday')],
            ['value' => 'thursday', 'label' => __('Thursday')],
            ['value' => 'friday', 'label' => __('Friday')],
            ['value' => 'saturday', 'label' => __('Saturday')],
            ['value' => 'sunday', 'label' => __('Sunday')]
        ];
    }
}
