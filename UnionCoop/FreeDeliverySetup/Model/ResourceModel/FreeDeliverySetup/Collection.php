<?php

namespace Unioncoop\FreeDeliverySetup\Model\ResourceModel\FreeDeliverySetup;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Unioncoop\FreeDeliverySetup\Model\FreeDeliverySetup;
use Unioncoop\FreeDeliverySetup\Model\ResourceModel\FreeDeliverySetup as FreeDeliverySetupResourceModel;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Data\Collection as DbCollection;
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {  $this->_init(
        FreeDeliverySetup::class,
        FreeDeliverySetupResourceModel::class
    );
        $this->_map['fields']['id'] = 'main_table.id';
    }
}
