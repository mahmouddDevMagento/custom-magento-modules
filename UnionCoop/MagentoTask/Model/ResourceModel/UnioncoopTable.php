<?php

namespace  UnionCoop\MagentoTask\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class UnioncoopTable extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('unioncoop_table', 'id');
    }
}
