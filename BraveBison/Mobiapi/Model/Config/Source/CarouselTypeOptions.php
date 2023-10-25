<?php
namespace BraveBison\Mobiapi\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class CarouselTypeOptions implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'image', 'label' => __('Image')],
            ['value' => 'product', 'label' => __('Product')]
        ];
    }
}