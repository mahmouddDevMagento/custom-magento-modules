<?php

namespace BraveBison\Mobiapi\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;


class Appcreator extends \Magento\Framework\Model\AbstractModel
{
    const CACHE_TAG = "mobiapi_appcreator";
    protected $_cacheTag = "mobiapi_appcreator";
    protected $_eventPrefix = "mobiapi_appcreator";

    protected function _construct()
    {
        $this->_init(\BraveBison\Mobiapi\Model\ResourceModel\Appcreator::class);
    }
}
