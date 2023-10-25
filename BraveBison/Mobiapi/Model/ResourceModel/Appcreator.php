<?php
namespace BraveBison\Mobiapi\Model\ResourceModel;

class Appcreator extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('mobiapi_appcreator', 'id');
    }
}
