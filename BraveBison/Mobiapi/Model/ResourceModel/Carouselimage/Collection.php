<?php

namespace BraveBison\Mobiapi\Model\ResourceModel\Carouselimage;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(
            \BraveBison\Mobiapi\Model\Carouselimage::class,
            \BraveBison\Mobiapi\Model\ResourceModel\Carouselimage::class
        );
        $this->_map['fields']['id'] = 'main_table.id';
    }


}
