<?php

namespace BestResponseMedia\FixProductDecimalPrice\Model;

use Magento\Framework\Pricing\PriceCurrencyInterface;

class PriceCurrency extends \Magento\Directory\Model\PriceCurrency implements PriceCurrencyInterface
{
    /**
     * @inheritdoc
     */
    const PRECISION_ZERO = 0;

    /**
     * {@inheritdoc}
     */
    public function format(
        $amount,
        $includeContainer = true,
        $precision = self::PRECISION_ZERO,
        $scope = null,
        $currency = null
    ) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $request = $objectManager->get(\Magento\Framework\App\Request\Http::class);
//        if ($request->getRouteName() == 'checkout' || $request->getFullActionName() == 'checkout_cart_index') {
//            return $this->getCurrency($scope, $currency)
//                ->formatPrecision($amount, 2 , [], $includeContainer);
//        } else {
//            return $this->getCurrency($scope, $currency)
//                ->formatPrecision($amount, $precision, [], $includeContainer);
//        }
        return $this->getCurrency($scope, $currency)
            ->formatPrecision($amount, $precision, [], $includeContainer);

    }
}
