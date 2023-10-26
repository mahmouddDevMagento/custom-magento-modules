<?php

namespace BestResponseMedia\CurrencySwitcher\Plugin\Currency;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;

class SwitchActionPlugin
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var CookieMetadataFactory
     */
    protected $cookieMetadataFactory;

    /**
     * @var SessionManagerInterface
     */
    protected $sessionManager;

    /**
     * @var \Magento\Framework\Locale\CurrencyInterface
     */
    protected $localeCurrency;

    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    protected $redirect;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     * @param CookieMetadataFactory $cookieMetadataFactory
     * @param SessionManagerInterface $sessionManager
     * @param \Magento\Framework\Locale\CurrencyInterface $localeCurrency
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface       $storeManager,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        CookieMetadataFactory                            $cookieMetadataFactory,
        SessionManagerInterface                          $sessionManager,
        \Magento\Framework\Locale\CurrencyInterface $localeCurrency,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig

    )
    {
        $this->storeManager = $storeManager;
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->sessionManager = $sessionManager;
        $this->localeCurrency = $localeCurrency;
        $this->redirect = $redirect;
        $this->_scopeConfig = $scopeConfig;
    }

    public function afterExecute(
        \Magento\Directory\Controller\Currency\SwitchAction $subject, $result)
    {
        $cookieCurrency = $this->cookieManager->getCookie(
            'currency_code'
        );
        $cookieCurrencyData = $this->cookieManager->getCookie(
            'currency_code_data'
        );
        if ($cookieCurrency) {
            $metadata = $this->cookieMetadataFactory
                ->createPublicCookieMetadata()
                ->setDuration(86400)
                ->setPath($this->sessionManager->getCookiePath())
                ->setDomain($this->sessionManager->getCookieDomain());
            $this->cookieManager->deleteCookie(
                'currency_code',
                $metadata
            );
        }

        if ($cookieCurrencyData){
            $metadata = $this->cookieMetadataFactory
                ->createPublicCookieMetadata()
                ->setDuration(86400)
                ->setPath($this->sessionManager->getCookiePath())
                ->setDomain($this->sessionManager->getCookieDomain());
            $this->cookieManager->deleteCookie(
                'currency_code_data',
                $metadata
            );
        }

        $currency = (string)$subject->getRequest()->getParam('currency');
        if ($currency) {

            $this->storeManager->getStore()->setCurrentCurrencyCode($currency);

            $currencySymbol = $this->localeCurrency->getCurrency($currency)->getSymbol();
            $currencyCode = $currency;
            $currencyCookie = $currencyCode .' '. $currencySymbol;

            $metadata = $this->cookieMetadataFactory
                ->createPublicCookieMetadata()
                ->setDuration(86400)
                ->setPath($this->sessionManager->getCookiePath())
                ->setDomain($this->sessionManager->getCookieDomain());
            $this->cookieManager->setPublicCookie(
                'currency_code',
                $currencyCookie,
                $metadata
            );

            $this->cookieManager->setPublicCookie(
                'currency_code_data',
                $currency,
                $metadata
            );
        }

        $storeUrl = $this->storeManager->getStore()->getBaseUrl();
        $subject->getResponse()->setRedirect($this->redirect->getRedirectUrl($storeUrl));
//        $subject->getResponse()->setRedirect($this->redirect->getRefererUrl());

        return $result;
    }
}
