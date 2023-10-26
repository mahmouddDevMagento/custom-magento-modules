<?php

namespace BestResponseMedia\CurrencySwitcher\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use BestResponseMedia\CurrencySwitcher\Helper\Data as BrmHelper;
use BestResponseMedia\CustomThemeConfiguration\Helper\Currency as BrmHelperCurrency;
use Magento\Authorization\Model\UserContextInterface;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Directory\Model\CountryFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

class CustomerLogin implements ObserverInterface
{

    /**
     * Name of cookie that holds private content version
     */
    const COOKIE_NAME = 'currency_popup';

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customer;
    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    protected $_countryFactory;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var SessionManagerInterface
     */
    protected $sessionManager;
    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;
    /**
     * @var BrmHelper
     */
    protected $brmHelper;
    /**
     * @var BrmHelperCurrency
     */
    protected $brmHelperCurrency;
    /**
     * @var UserContextInterface
     */
    protected $userContext;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepository;
    /**
     * @var CountryFactory
     */
    private $countryFactory;
    /**
     * @var CookieMetadataFactory
     */
    private $cookieMetadataFactory;
    /**
     * CookieManager
     *
     * @var CookieManagerInterface
     */
    private $cookieManager;

    /**
     * @param \Magento\Customer\Model\Session $customer
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface         $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Http\Context                $httpContext,
        \Magento\Customer\Model\Session                    $customer,
        CustomerRepositoryInterface                        $customerRepository,
        AddressRepositoryInterface                         $addressRepository,
        CountryFactory                                     $countryFactory,
        CookieMetadataFactory                              $cookieMetadataFactory,
        SessionManagerInterface                            $sessionManager,
        CookieManagerInterface                             $cookieManager,
        BrmHelperCurrency                                  $brmHelperCurrency,
        UserContextInterface                               $userContext
    )
    {
        $this->customer = $customer;
        $this->customerRepository = $customerRepository;
        $this->addressRepository = $addressRepository;
        $this->countryFactory = $countryFactory;
        $this->_countryFactory = $countryFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->sessionManager = $sessionManager;
        $this->httpContext = $httpContext;
        $this->cookieManager = $cookieManager;
        $this->_countryFactory = $countryFactory;
        $this->brmHelperCurrency = $brmHelperCurrency;
        $this->userContext = $userContext;
    }

    public function execute(Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        $customerId = $customer->getId();

//         var_dump($customerId);die();
        $customer = $this->customerRepository->getById($customerId);
        $shippingAddressId = $customer->getDefaultShipping();
        $shippingAddress = $this->addressRepository->getById($shippingAddressId);

        $storeManagerDataList = $this->storeManager->getStores();

        foreach ($storeManagerDataList as $store) {


            $storeCountry = $this->_scopeConfig->getValue("general/country/destinations",
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store->getId());

            $storeCountries = explode(',', $storeCountry);

            $countryCode = $shippingAddress->getCountryId();
            $country = $this->countryFactory->create()->loadByCode($countryCode);
            $country->getName();

            if (in_array($countryCode, $storeCountries)) {
                $url = $this->_scopeConfig->getValue("web/secure/base_url",
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store->getId());

                $this->setCookie(true);
                $this->setCountryCookie($countryCode);
                $this->setCountryCodeCookie($countryCode);

                $this->httpContext->setValue(
                    'country_code',
                    $countryCode,
                    false
                );

                $currencyCode = $this->storeManager->getStore()->getCurrentCurrencyCode();

                $currencySymbole = $this->brmHelperCurrency->getCurrencySymbol($currencyCode);
                $currencyCode = $currencyCode;
                $currencyCookie = $currencyCode . ' ' . $currencySymbole;

                $this->customer->setCountry(true);
                $this->setCurrencyCookie($currencyCookie);
                $this->setCurrency($currencyCode);

//                return $result;

            }


        }

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

}