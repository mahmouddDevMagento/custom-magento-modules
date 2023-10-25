<?php

namespace BraveBison\Mobiapi\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use BraveBison\Mobiapi\Model\Bannerimage;

class StatusOptions implements OptionSourceInterface
{

    /**
     * @var Bannerimage
     */
    protected $banner;

    public function __construct(Bannerimage $banner)
    {
        $this->banner = $banner;
    }

//    public function toOptionArray()
//    {
//        return [
//            ['value' => '1', 'label' => __('Enabled')],
//            ['value' => '0', 'label' => __('Disabled')]
//        ];
//    }
    public function toOptionArray()
    {
        $availableOptions = $this->banner->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
