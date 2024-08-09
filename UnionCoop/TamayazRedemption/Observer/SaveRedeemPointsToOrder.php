<?php

namespace Unioncoop\TamayazRedemption\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteRepository;
use Magento\Checkout\Model\Session as CheckoutSession;
use Unioncoop\Singlesignon\Helper\Data as SinglesignonHelper;
use Magento\Framework\Exception\LocalizedException;
class SaveRedeemPointsToOrder implements ObserverInterface
{
    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var QuoteRepository
     */
    protected $quoteRepository;

    public function __construct(
        CheckoutSession $checkoutSession,
        QuoteRepository $quoteRepository,
        SinglesignonHelper $singlesignonHelper
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->quoteRepository = $quoteRepository;
        $this->singlesignonHelper = $singlesignonHelper;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $tamayazRedemptionData = $this->getTamayazRedemptionData();

        $quote = $this->getQuote($order);

        $requestedRedeemPointsAmount = $quote->getRedeemPointsAmount();
        $actualRedeemPointsAmount = $tamayazRedemptionData['data']['customerDetails']['redemptionAmount'] ?? 0;

        // Validate if the requested redeem points amount is greater than the actual redeem points amount
        if ($requestedRedeemPointsAmount > $actualRedeemPointsAmount) {
            throw new LocalizedException(__('The requested redeem points amount exceeds the available redemption amount.'));
        }

        // Set redeem points and amount to the order
        $order->setRedeemPoints($quote->getRedeemPoints());
        $order->setRedeemPointsAmount($quote->getRedeemPointsAmount());

        return $this;
    }

    /**
     * Get Tamayaz redemption data
     *
     * @return array
     */
    private function getTamayazRedemptionData(): array
    {
        $requestData = ['showPointsDetail' => true];
        return $this->singlesignonHelper->getTamayazRedemptionData($requestData);
    }

    private function getQuote($order)
    {
        // Try to get the quote from the checkout session (for web)
        $quote = $this->checkoutSession->getQuote();

        // Check if the quote ID is null or invalid, which happens with API calls
        if (!$quote->getId()) {
            // Retrieve the quote from the order itself (for API)
            $quote = $this->quoteRepository->get($order->getQuoteId());
        }

        return $quote;
    }
}
