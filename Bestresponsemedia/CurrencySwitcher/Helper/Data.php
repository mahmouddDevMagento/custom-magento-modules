<?php

namespace BestResponseMedia\CurrencySwitcher\Helper;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Directory\Model\CurrencyFactory;
use BestResponseMedia\CurrencySwitcher\Helper\Address as BrmAddressHelper;
use Magento\Framework\Controller\ResultFactory;

class Data extends AbstractHelper
{

    /**
     * @var \Magento\Directory\Model\ResourceModel\Country\CollectionFactory
     */
    protected $_countryCollectionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\Locale\CurrencyInterface
     */
    protected $_currency;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_coreSession;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Directory\Model\CurrencyFactory
     */
    protected $currencyFactory;

    /**
     * @var BrmAddressHelper
     */
    protected $brmAddress;

    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    protected $_countryFactory;

    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface CookieManagerInterface
     */
    private $cookieManager;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory CookieMetadataFactory
     */
    private $cookieMetadataFactory;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * @var CheckoutSession
     */
    protected $_checkoutSession;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;


    /**
     * @param \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Locale\CurrencyInterface $currency
     * @param \Magento\Customer\Model\Session $coreSession
     * @param Context $context
     */
    public function __construct(
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Locale\CurrencyInterface $currency,
        \Magento\Customer\Model\Session $coreSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        Context $context,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        BrmAddressHelper $brmAddress,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        \Magento\Framework\App\Http\Context $httpContext,
        CheckoutSession $checkoutSession,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->_countryCollectionFactory = $countryCollectionFactory;
        $this->_storeManager = $storeManager;
        $this->_coreSession = $coreSession;
        $this->_currency = $currency;
        $this->_scopeConfig = $scopeConfig;
        $this->currencyFactory = $currencyFactory;
        $this->brmAddress = $brmAddress;
        $this->_countryFactory = $countryFactory;
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->httpContext = $httpContext;
        $this->_checkoutSession = $checkoutSession;
        $this->_logger = $logger;
        parent::__construct($context);
    }

    public function getCountries()
    {
        $collection = $this->_countryCollectionFactory->create()->loadByStore()->toOptionArray();
        return $collection;
    }


    public function getAllCurrency()
    {
        $availableCurrencies = $this->_storeManager->getStore()->getAvailableCurrencyCodes();
        return $availableCurrencies;
    }

    public function getCurrencyData()
    {
        return $this->_currency;
    }

    public function getCountryPopup()
    {
//        return $this->_coreSession->getCountry();
        return $this->cookieManager->getCookie(
            'currency_popup'
        );
    }

    public function getCountryCode()
    {
//        return $this->_coreSession->getCountry();
        return $this->cookieManager->getCookie(
            'country_code'
        );
    }

    public function getCurrencCode()
    {
//        return $this->_coreSession->getCountry();
        return $this->cookieManager->getCookie(
            'currency_code_data'
        );
    }

    public function getAllStores()
    {
        $stores = $this->_storeManager->getStores();
        $storeCountries = [];

        foreach ($stores as $store) {
            $storeCountries[$store->getCode()] = [
                $store->getId() => $this->_scopeConfig->getValue("general/country/destinations",
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store->getId())
            ];
        }

        return $storeCountries;
    }

    public function getGeoIpData()
    {
        return $this->httpContext->getValue('brm_geoip');
    }

    public function getCurrentCurrency()
    {
        $customerAddress = $this->getGeoIpData();
        $allCurrencies = $this->getAllCurrency();
        $currencyCode = '';
        $countryCode = '';
        $countryName = '';
        if (isset($customerAddress['currency_code'])) {
            if (!in_array($customerAddress['currency_code'], $allCurrencies)) {
                $currencyCode = $this->_scopeConfig->getValue("currency/options/default",
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE, 1);
            } else {
                $currencyCode = $customerAddress['currency_code'];
            }
//        $countryCheck = $this->checkCountry($customerAddress['country_id']);
            if ($customerAddress['country_id']) {
                $countryCode = $customerAddress['currency_code'];
                $country = $this->_countryFactory->create()->loadByCode($customerAddress['country_id']);
                $countryName = $country->getName();
            } else {
                $countryCode = $this->_scopeConfig->getValue("general/country/default",
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE, 1);
                $country = $this->_countryFactory->create()->loadByCode($countryCode);
                $countryName = $country->getName();
            }
            $currency = $this->currencyFactory->create()->load($currencyCode);
            $currencySymbol = $currency->getCurrencySymbol();

            $country = $this->_countryFactory->create()->loadByCode($customerAddress['country_id']);
            $data = [
                'country' => $countryName,
                'currency_symbol' => $currencySymbol,
                'currency_code' => $currencyCode,
                'country_code' => $countryCode
            ];
            return $data;
        }

    }

    public function getDefaultCountry()
    {
        $customerAddress = $this->brmAddress->getGeoIpData();
        if (isset($customerAddress['currency_code'])) {
            $currency = $this->currencyFactory->create()->load($customerAddress['currency_code']);
            $currencySymbol = $currency->getCurrencySymbol();

//        $country = $this->_countryFactory->create()->loadByCode($customerAddress['country_id']);
            $country = $this->_scopeConfig->getValue("general/country/default",
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $data = $this->_countryFactory->create()->loadByCode($country);
            return $data->getName();
        }

    }

    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    public function checkCountry($country)
    {
        $storeCountries = $this->getAllStores();
        if (is_array($storeCountries) && count($storeCountries) > 0) {
            foreach ($storeCountries as $key => $value) {
                foreach ($value as $k => $v) {
                    if ($v) {
                        $countries = explode(',', $v);
                        if (in_array($country, $countries)) {
                            return true;
                        }
                    }

                }
            }
        }
    }

    public function getDefaultStore()
    {
        return $this->getStoreData('general/country/default');
    }

    public function getCurrentCurrencySymbol()
    {
        $code = $this->_storeManager->getStore()->getCurrentCurrencyCode();
        return $this->_currency->getCurrency($code)->getSymbol();
    }

    public function getCurrentCurrencyCode()
    {
        $code = $this->_storeManager->getStore()->getCurrentCurrencyCode();
        return $code;
    }

    public function getStoreData($data)
    {
        return $this->_scopeConfig->getValue($data, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getCountryByCookies()
    {
        return $this->cookieManager->getCookie('shipping_county');
    }

}
