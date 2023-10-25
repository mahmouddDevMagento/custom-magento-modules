<?php
namespace BraveBison\Mobiapi\Model\ResourceModel;

class Bannerimage extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        $this->_init("mobiapi_bannerimage", "id");
    }

}
