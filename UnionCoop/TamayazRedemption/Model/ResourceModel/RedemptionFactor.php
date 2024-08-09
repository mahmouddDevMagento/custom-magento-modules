<?php

namespace Unioncoop\TamayazRedemption\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class RedemptionFactor extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('tamayaz_redemption_factor', 'id');
    }
}
