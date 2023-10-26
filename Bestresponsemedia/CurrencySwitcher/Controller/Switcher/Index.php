<?php

namespace BestResponseMedia\CurrencySwitcher\Controller\Switcher;

use Magento\Framework\Controller\ResultFactory;
use Magento\Checkout\Model\Session as CheckoutSession;
use BestResponseMedia\CurrencySwitcher\Helper\Data as BrmHelper;
use BestResponseMedia\CustomThemeConfiguration\Helper\Currency as BrmHelperCurrency;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Session\SessionManagerInterface;

class Index extends \Magento\Framework\App\Action\Action
{

    /**
     * Name of cookie that holds private content version
     */
    const COOKIE_NAME = 'currency_popup';


    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_coreSession;

    /**
     * @var CheckoutSession
     */
    protected $_checkoutSession;

    /**
     * @var BrmHelper
     */
    protected $brmHelper;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * CookieManager
     *
     * @var CookieManagerInterface
     */
    private $cookieManager;


    /**
     * @var CookieMetadataFactory
     */
    private $cookieMetadataFactory;

    /**
     * @var SessionManagerInterface
     */
    protected $sessionManager;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    protected $_countryFactory;


    protected $brmHelperCurrency;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlInterface;


    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\Session $coreSession
     * @param \Magento\Framework\App\Action\Context $context
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface         $storeManager,
        \Magento\Customer\Model\Session                    $coreSession,
        \Magento\Framework\App\Action\Context              $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        CheckoutSession                                    $checkoutSession,
        BrmHelper                                          $brmHelper,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        SessionManagerInterface $sessionManager,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        BrmHelperCurrency $brmHelperCurrency,
        \Magento\Framework\UrlInterface $urlInterface,

    )
    {
        $this->storeManager = $storeManager;
        $this->_coreSession = $coreSession;
        $this->_checkoutSession = $checkoutSession;
        $this->brmHelper = $brmHelper;
        $this->_scopeConfig = $scopeConfig;
        $this->cookieManager         = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->sessionManager        = $sessionManager;
        $this->httpContext = $httpContext;
        $this->_countryFactory = $countryFactory;
        $this->brmHelperCurrency = $brmHelperCurrency;
        $this->urlInterface = $urlInterface;
        return parent::__construct($context);
    }

    public function execute()
    {
        $country = $this->getRequest()->getparam('country');
        $currency = $this->getRequest()->getparam('currency');
        if ($currency) {
            $this->storeManager->getStore()->setCurrentCurrencyCode($currency);
            $this->_coreSession->setCountry(true);
           $currencySymbole = $this->brmHelperCurrency->getCurrencySymbol($currency);
           $currencyCode = $currency;
           $currencyCookie = $currencyCode .' '. $currencySymbole;

           $this->setCurrencyCookie($currencyCookie);
           $this->setCurrency($currency);
        }

        $allCountry =  $this->_scopeConfig->getValue("general/country/destinations",
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $storeManagerDataList = $this->storeManager->getStores();

        foreach ($storeManagerDataList as $store){
            $storeCountry =  $this->_scopeConfig->getValue("general/country/destinations",
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE , $store->getId());

            $storeCountries = explode(',', $storeCountry);

            if(in_array($country , $storeCountries)){


                $url = $this->_scopeConfig->getValue("web/secure/base_url",
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store->getId());

                $this->setCookie(true);
                $this->setCountryCookie($country);
                $this->setCountryCodeCookie($country);
                $this->httpContext->setValue(
                    'country_code',
                    $country,
                    false
                );
//                var_dump($country);die();
                $quote = $this->_checkoutSession->getQuote();
                $quote->getShippingAddress()->setCountryId($country)->save();

//                var_dump($quote->getShippingAddress()->getCountryId());die();




//                var_dump( $quote->getId());
//                die();
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

                //redirect to the same page on requested store
                //get requested url path
                $requestUrl = parse_url($this->_redirect->getRefererUrl(), PHP_URL_PATH);
                $baseurl = $url;

                if(strpos($url, '/') !== false) {
                    $baseurl = rtrim($url, "/");
                }

                if(strpos($requestUrl, '/uk/') !== false){
                    $requestUrl = str_replace('/uk/','',$requestUrl);
                    $baseurl = $url ;
                }

                $resultRedirect->setUrl($baseurl.$requestUrl);
//                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                return $resultRedirect;

            }
        }


        $this->setCookie(true);
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;


    }


    /**
     * Set Custom Cookie Value
     *
     * @param boolean $value
     * @return void
     */
    private function setCookie($value)
    {
        $metadata = $this->cookieMetadataFactory
            ->createPublicCookieMetadata()
            ->setDuration(86400)
            ->setPath($this->sessionManager->getCookiePath())
            ->setDomain($this->sessionManager->getCookieDomain());
        $this->cookieManager->setPublicCookie(
            self::COOKIE_NAME,
            $value,
            $metadata
        );
    }


    /**
     * Set Custom Cookie Value
     *
     * @param boolean $value
     * @return void
     */
    private function setCurrencyCookie($value)
    {
        $metadata = $this->cookieMetadataFactory
            ->createPublicCookieMetadata()
            ->setDuration(86400)
            ->setPath($this->sessionManager->getCookiePath())
            ->setDomain($this->sessionManager->getCookieDomain());
        $this->cookieManager->setPublicCookie(
            'currency_code',
            $value,
            $metadata
        );
    }



    /**
     * Set Custom Cookie Value
     *
     * @param boolean $value
     * @return void
     */
    private function setCountryCookie($value)
    {
        $country = $this->_countryFactory->create()->loadByCode($value);
        $countryName = $country->getName();
        $metadata = $this->cookieMetadataFactory
            ->createPublicCookieMetadata()
            ->setDuration(86400)
            ->setPath($this->sessionManager->getCookiePath())
            ->setDomain($this->sessionManager->getCookieDomain());
        $this->cookieManager->setPublicCookie(
            'shipping_county',
            $countryName,
            $metadata
        );
    }

    private function setCountryCodeCookie($value)
    {
        $metadata = $this->cookieMetadataFactory
            ->createPublicCookieMetadata()
            ->setDuration(86400)
            ->setPath($this->sessionManager->getCookiePath())
            ->setDomain($this->sessionManager->getCookieDomain());
        $this->cookieManager->setPublicCookie(
            'country_code',
            $value,
            $metadata
        );
    }

    private function setCurrency($value)
    {
        $metadata = $this->cookieMetadataFactory
            ->createPublicCookieMetadata()
            ->setDuration(86400)
            ->setPath($this->sessionManager->getCookiePath())
            ->setDomain($this->sessionManager->getCookieDomain());
        $this->cookieManager->setPublicCookie(
            'currency_code_data',
            $value,
            $metadata
        );
    }


    public function getCurrentUrl()
    {
        return $this->urlInterface->getCurrentUrl();
    }

}
