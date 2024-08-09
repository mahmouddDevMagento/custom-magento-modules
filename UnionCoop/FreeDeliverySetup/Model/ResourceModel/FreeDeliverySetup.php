<?php
namespace Unioncoop\FreeDeliverySetup\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class FreeDeliverySetup extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('free_delivery_setup', 'id');
    }
}
