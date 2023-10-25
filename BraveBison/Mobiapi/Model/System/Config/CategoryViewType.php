<?php
namespace BraveBison\Mobiapi\Model\System\Config;

use Magento\Framework\Option\ArrayInterface;

class CategoryViewType implements ArrayInterface
{
    public function toOptionArray() {
        return [
            ['value' => 0 ,'label' => __('default category view ')],
            ['value' => 1 ,'label' => __('tap category view')]
        ];
    }
}
