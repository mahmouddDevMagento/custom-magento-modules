<?php
namespace BraveBison\Mobiapi\Model\System\Config\Backend;

use Magento\Framework\App\Config\Value;

class VersionValidator extends Value
{
    public function beforeSave()
    {
        $newValue = $this->getValue();
        $oldValue = $this->getOldValue();
        if ($newValue <= $oldValue) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('The new version must be greater than the previous version.')
            );
        }

        return parent::beforeSave();
    }
}
