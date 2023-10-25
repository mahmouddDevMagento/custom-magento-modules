<?php
namespace BraveBison\Mobiapi\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Carousel extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('mobiapi_carousel', 'id');
    }
}
