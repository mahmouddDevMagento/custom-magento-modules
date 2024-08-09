<?php


namespace Unioncoop\TamayazRedemption\Controller\Quote;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Quote\Model\QuoteRepository;
use Magento\Checkout\Model\Session as CheckoutSession;

class SaveRedeemPoints extends Action
{
    protected $resultJsonFactory;
    protected $quoteRepository;
    protected $checkoutSession;

    public function __construct(
        Context         $context,
        JsonFactory     $resultJsonFactory,
        QuoteRepository $quoteRepository,
        CheckoutSession $checkoutSession
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->quoteRepository = $quoteRepository;
        $this->checkoutSession = $checkoutSession;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        try {
            $this->updateQuote();

            $result->setData(['success' => true, 'message' => __('Redeem points saved successfully.')]);
        } catch (\Exception $e) {
            $result->setData(['success' => false, 'message' => $e->getMessage()]);
        }

        return $result;
    }


    private function updateQuote(): void
    {
        $quote = $this->checkoutSession->getQuote();
        $params = $this->getRequest()->getParams();

        $redeemPointsAmount = isset($params['redeem_points_amount']) ? (float)$params['redeem_points_amount'] : 0;
        $redeemPoints = (int)$params['redeem_points'];

//        $usedAmount = min($redeemPointsAmount, $grandTotal);

        $quote->setData('redeem_points', $redeemPoints);
        $quote->setData('redeem_points_amount', $redeemPointsAmount);

        if (!$this->isRedemptionSwitchEnabled()) {
            $quote->setData('use_redeem_points_amount', 0);
        }

        $this->quoteRepository->save($quote);
    }

    private function isRedemptionSwitchEnabled(): bool
    {
        $params = $this->getRequest()->getParams();
        return filter_var($params['redemptionSwitchEnabled'], FILTER_VALIDATE_BOOLEAN);
    }
}
