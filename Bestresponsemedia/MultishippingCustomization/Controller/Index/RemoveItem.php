<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace BestResponseMedia\MultishippingCustomization\Controller\Index;

use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Class RemoveItem
 *
 * Removes multishipping items
 */
class RemoveItem extends \Magento\Multishipping\Controller\Checkout implements HttpPostActionInterface
{
    /**
     * Multishipping checkout remove item action
     *
     * @return void
     */
    public function execute()
    {
        $itemId = $this->getRequest()->getParam('id');
        $addressId = $this->getRequest()->getParam('address');
        if ($addressId && $itemId) {
            $this->_getCheckout()->setCollectRatesFlag(true);
            $this->_getCheckout()->splitAddressItem($addressId, $itemId);
        }
        $this->_redirect('multishipping/checkout/addresses');
    }
}
