<?php
namespace BraveBison\Mobiapi\Model\ResourceModel\Carousel;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(
            \BraveBison\Mobiapi\Model\Carousel::class,
            \BraveBison\Mobiapi\Model\ResourceModel\Carousel::class
        );
        $this->_map['fields']['id'] = 'main_table.id';
    }
}
