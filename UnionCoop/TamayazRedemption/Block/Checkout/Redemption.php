<?php
namespace Unioncoop\TamayazRedemption\Block\Checkout;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Redemption extends Template
{
    const XML_PATH_TAMAYAZ_ENABLE = 'tamayaz_redemption/general/enable';

    protected $scopeConfig;

    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    public function isTamayazRedemptionEnabled()
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_TAMAYAZ_ENABLE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    protected function _toHtml()
    {
        if (!$this->isTamayazRedemptionEnabled()) {
            return '';
        }
        return parent::_toHtml();
    }
}
