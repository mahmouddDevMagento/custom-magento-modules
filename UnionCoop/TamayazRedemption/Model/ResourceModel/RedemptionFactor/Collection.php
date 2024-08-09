<?php

namespace Unioncoop\TamayazRedemption\Model\ResourceModel\RedemptionFactor;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Unioncoop\TamayazRedemption\Model\RedemptionFactor as Model;
use Unioncoop\TamayazRedemption\Model\ResourceModel\RedemptionFactor as ResourceModel;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
