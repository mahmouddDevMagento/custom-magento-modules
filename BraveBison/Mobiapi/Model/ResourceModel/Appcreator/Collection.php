<?php
namespace BraveBison\Mobiapi\Model\ResourceModel\Appcreator;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(
            \BraveBison\Mobiapi\Model\Appcreator::class,
            \BraveBison\Mobiapi\Model\ResourceModel\Appcreator::class
        );
        $this->_map['fields']['id'] = 'main_table.id';
    }
}
