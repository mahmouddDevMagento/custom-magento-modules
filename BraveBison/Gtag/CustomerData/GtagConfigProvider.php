<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace BraveBison\Gtag\CustomerData;

use \Magento\Checkout\Model\ConfigProviderInterface;
class GtagConfigProvider implements ConfigProviderInterface
{

    public function getConfig()
    {
        $configArray = [];
        $configArray['custom_data'] = 'working';
        return $configArray;
    }
}
