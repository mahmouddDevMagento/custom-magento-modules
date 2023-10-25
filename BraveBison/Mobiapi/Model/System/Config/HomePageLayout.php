<?php
namespace BraveBison\Mobiapi\Model\System\Config;

use Magento\Framework\Option\ArrayInterface;

class HomePageLayout implements ArrayInterface
{
    public function toOptionArray() {
        return [
            ['value' => 0 ,'label' => __('layout one')],
            ['value' => 1 ,'label' => __('layout two')]
        ];
    }
}
