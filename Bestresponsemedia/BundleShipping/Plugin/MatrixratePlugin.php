<?php
namespace BestResponseMedia\BundleShipping\Plugin;

use Magento\Quote\Model\Quote\Address\RateRequest;
use WebShopApps\MatrixRate\Model\Carrier\Matrixrate ;

class MatrixratePlugin
{
//    public function beforeCollectRates(Matrixrate $subject, RateRequest $request)
//    {
//        // You can add your custom code here to be executed before the original collectRates method.
//        // If you want to modify the $request object, you can do so here.
//        // Example:
//        // $request->setSomeData('value');
//    }

    public function afterCollectRates(Matrixrate $subject, RateRequest $request, $result)
    {
        if (!$subject->getConfigFlag('active')) {
            return false;
        }

        // exclude Virtual products price from Package value if pre-configured
        if (!$subject->getConfigFlag('include_virtual_price') && $request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {
                if ($item->getParentItem()) {
                    continue;
                }
                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getProduct()->isVirtual()) {
                            $request->setPackageValue($request->getPackageValue() - $child->getBaseRowTotal());
                        }
                    }
                } elseif ($item->getProduct()->isVirtual()) {
                    $request->setPackageValue($request->getPackageValue() - $item->getBaseRowTotal());
                }
            }
        }

        // Free shipping by qty
        $freeQty = 0;
        $isBundleProduct=false;
        if ($request->getAllItems()) {
            $freePackageValue = 0;
            foreach ($request->getAllItems() as $item) {
                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }
                if ($item->getProductType() == \Magento\Catalog\Model\Product\Type::TYPE_BUNDLE) {
                    $isBundleProduct = true;
                    break;
                }

                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                            $freeShipping = is_numeric($child->getFreeShipping()) ? $child->getFreeShipping() : 0;
                            $freeQty += $item->getQty() * ($child->getQty() - $freeShipping);
                        }
                    }
                } elseif ($item->getFreeShipping()) {
                    $freeShipping = is_numeric($item->getFreeShipping()) ? $item->getFreeShipping() : 0;
                    $freeQty += $item->getQty() - $freeShipping;
                    $freePackageValue += $item->getBaseRowTotal();
                }
            }
            $oldValue = $request->getPackageValue();
            $request->setPackageValue($oldValue - $freePackageValue);
        }

        if (!$request->getConditionMRName()) {
            $conditionName = $subject->getConfigData('condition_name');
            $request->setConditionMRName($conditionName ? $conditionName : $subject->defaultConditionName);
        }

        // Package weight and qty free shipping
        $oldWeight = $request->getPackageWeight();
        $oldQty = $request->getPackageQty();

        $request->setPackageWeight($request->getFreeMethodWeight());
        $request->setPackageQty($oldQty - $freeQty);

        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $subject->rateResultFactory->create();
        $zipRange = $subject->getConfigData('zip_range');
        $rateArray = $subject->getRate($request, $zipRange);

        $request->setPackageWeight($oldWeight);
        $request->setPackageQty($oldQty);

        $foundRates = false;

        foreach ($rateArray as $rate) {
            if (!empty($rate) && $rate['price'] >= 0) {
                /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
                $method = $subject->resultMethodFactory->create();


                $method->setCarrier('matrixrate');
                $method->setCarrierTitle($subject->getConfigData('title'));

                $method->setMethod('matrixrate_' . $rate['pk']);
//                $method->setMethodTitle(__($rate['shipping_method']));
                if ($isBundleProduct) {
                    $method->setMethodTitle(__('International Priority (2-4 Days)'));
                } else {
                    $method->setMethodTitle(__($rate['shipping_method']));
                }

                if ($request->getFreeShipping() === true || $request->getPackageQty() == $freeQty) {
                    $shippingPrice = 0;
                } else {
                    $shippingPrice = $subject->getFinalPriceWithHandlingFee($rate['price']);
                }

                $method->setPrice($shippingPrice);
                $method->setCost($rate['cost']);

                $result->append($method);
                $foundRates = true; // have found some valid rates
            }
        }

        if (!$foundRates) {
            /** @var \Magento\Quote\Model\Quote\Address\RateResult\Error $error */
            $error = $subject->_rateErrorFactory->create(
                [
                    'data' => [
                        'carrier' => $subject->_code,
                        'carrier_title' => $subject->getConfigData('title'),
                        'error_message' => $subject->getConfigData('specificerrmsg'),
                    ],
                ]
            );
            $result->append($error);
        }

        return $result;
    }
}
