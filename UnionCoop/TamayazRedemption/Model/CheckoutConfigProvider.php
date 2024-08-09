<?php

namespace Unioncoop\TamayazRedemption\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Unioncoop\TamayazRedemption\Helper\Data as TamayazRedemptionHelper;
use Unioncoop\Singlesignon\Helper\Data as SinglesignonHelper;
use Magento\Customer\Model\Session as CustomerSession;
class CheckoutConfigProvider implements ConfigProviderInterface
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var TamayazRedemptionHelper
     */
    protected $tamayazRedemptionHelper;

    /**
     * @var SinglesignonHelper
     */
    protected $singlesignonHelper;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param TamayazRedemptionHelper $tamayazRedemptionHelper
     * @param SinglesignonHelper $singlesignonHelper
     * @param CustomerSession $customerSession
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        TamayazRedemptionHelper $tamayazRedemptionHelper,
        SinglesignonHelper $singlesignonHelper,
        CustomerSession $customerSession
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->tamayazRedemptionHelper = $tamayazRedemptionHelper;
        $this->singlesignonHelper = $singlesignonHelper;
        $this->customerSession = $customerSession;
    }

    /**
     * Get configuration for Tamayaz redemption
     *
     * @return array
     */
    public function getConfig(): array
    {
        $isEnabled = $this->isRedemptionEnabled();
        $isLoggedIn = $this->customerSession->isLoggedIn();

        $tamayazRedemptionData = [];
        $showPointsDetails = false;
        $canRedeem = false;

        if ($isEnabled && $isLoggedIn) {
            $tamayazRedemptionData = $this->getTamayazRedemptionData();

            $tamayazRedemptionFlag = $tamayazRedemptionData['data']['customerDetails']['tamayazRedemptionFlag'];
            $showPointsDetails = $tamayazRedemptionFlag != 0;
            $canRedeem = $tamayazRedemptionFlag === 2;
        }

        return [
            'TamayazRedemption' => [
                'block_title' => $this->getBlockTitle(),
                'redeem_points' => $tamayazRedemptionData['data']['customerDetails']['points'] ?? 0,
                'redeem_points_amount' => $tamayazRedemptionData['data']['customerDetails']['redemptionAmount'] ?? 0,
                'use_redeem_points_amount' => $this->tamayazRedemptionHelper->getUseRedeemPointsAmount(),
                'showPointsDetails' => $showPointsDetails,
                'canRedeem' => $canRedeem,
            ],
        ];
    }

    /**
     * Check if Tamayaz redemption is enabled
     *
     * @return bool
     */
    private function isRedemptionEnabled(): bool
    {
        return (bool)$this->scopeConfig->getValue('tamayaz_redemption/general/enable', ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get block title for Tamayaz redemption
     *
     * @return string
     */
    private function getBlockTitle(): string
    {
        return (string)$this->scopeConfig->getValue('tamayaz_redemption/general/block_title', ScopeInterface::SCOPE_STORE);
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
}
