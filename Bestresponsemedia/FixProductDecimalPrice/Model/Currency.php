<?php
namespace BestResponseMedia\FixProductDecimalPrice\Model;

class Currency extends \Magento\Directory\Model\Currency
{

    /*
    * You can set precision from here in $options array
    */
    public function formatTxt($price, $options = [])
    {
        if (!is_numeric($price)) {
            $price = $this->_localeFormat->getNumber($price);
        }
        $price = sprintf("%F", $price);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $request = $objectManager->get(\Magento\Framework\App\Request\Http::class);
//        if ($request->getRouteName() == 'checkout' || $request->getFullActionName() == 'checkout_cart_index') {
//            $options['precision'] = 2;
//        } else {
//            $options['precision'] = 0;
//        }
        $options['precision'] = 0;

        return $this->_localeCurrency->getCurrency($this->getCode())->toCurrency($price, $options);
    }
}
