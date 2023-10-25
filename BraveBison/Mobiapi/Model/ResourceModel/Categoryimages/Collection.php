<?php

namespace BraveBison\Mobiapi\Model\ResourceModel\Categoryimages;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;


class Collection extends AbstractCollection
{
    protected $_idFieldName = "id";

    protected function _construct()
    {
        $this->_init(
            \BraveBison\Mobiapi\Model\Categoryimages::class,
            \BraveBison\Mobiapi\Model\ResourceModel\Categoryimages::class
        );
        $this->_map["fields"]["id"] = "main_table.id";
    }


}
