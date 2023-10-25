<?php

namespace BraveBison\Mobiapi\Model\ResourceModel;

class Walkthrough extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init("mobiapi_walkthrough", "id");
    }

}
