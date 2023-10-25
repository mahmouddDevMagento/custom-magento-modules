<?php

namespace BestResponseMedia\MultishippingCustomization\Model\Checkout\Type;

use Magento\Multishipping\Model\Checkout\Type\Multishipping as customMultishipping;

class Multishipping extends customMultishipping
{

    /**
     * split item
     *
     * @param int $addressId
     * @param int $itemId
     * @return \Magento\Multishipping\Model\Checkout\Type\Multishipping
     */
    public function splitAddressItem($addressId, $itemId)
    {
        $address = $this->getQuote()->getAddressById($addressId);
        /* @var $address \Magento\Quote\Model\Quote\Address */
        if ($address) {
            $item = $address->getValidItemById($itemId);
            if ($item) {
                $quoteItem = $this->getQuote()->getItemById($item->getQuoteItemId());
                if ($quoteItem) {
                    foreach ($address->getAllItems() as $item) {

                        if ($item->getId() == $itemId) {
                            if ($item->getParentItemId()) {
                                continue;
                            }
                            if ($item->getProduct()->getIsVirtual()) {
                                $items[] = $item;
                                continue;
                            }
                            $item->setQty($item->getQty() - 1);
                            $addressItem = clone $item;
                            $addressItem->setQty(1)->setCustomerAddressId($address->getCustomerAddressId())->save();
                            $items[] = $addressItem;
                        }

                    }
                    $this->_quoteShippingAddressesItems = $items;
                    $this->getQuote()->collectTotals();
                }
                $this->save();
            }
        }
        return $this;
    }
}
