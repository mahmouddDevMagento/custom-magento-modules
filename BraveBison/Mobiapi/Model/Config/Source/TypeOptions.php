<?php
namespace BraveBison\Mobiapi\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class TypeOptions implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'product', 'label' => __('Product')],
            ['value' => 'category', 'label' => __('Category')]
        ];
    }
}