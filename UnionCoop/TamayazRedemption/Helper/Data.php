<?php


namespace Unioncoop\TamayazRedemption\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Checkout\Model\Session as CheckoutSession;

class Data extends AbstractHelper
{
    protected $checkoutSession;

    public function __construct(
        CheckoutSession $checkoutSession
    )
    {
        $this->checkoutSession = $checkoutSession;
    }

    public function getUseRedeemPointsAmount()
    {
        $quote = $this->checkoutSession->getQuote();
        return (bool)$quote->getData('use_redeem_points_amount');
    }
}
