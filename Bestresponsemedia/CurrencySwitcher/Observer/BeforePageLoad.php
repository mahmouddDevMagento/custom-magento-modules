<?php

namespace BestResponseMedia\CurrencySwitcher\Observer;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\View\LayoutInterface;
use BestResponseMedia\CurrencySwitcher\Helper\Address;
use Magento\Framework\Controller\ResultFactory;
use BestResponseMedia\CurrencySwitcher\Helper\Data as BrmHelper;

class BeforePageLoad implements ObserverInterface
{
    private $helper;


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

    protected $resultFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface         $storeManager,
        \Magento\Customer\Model\Session                    $coreSession,
        \Magento\Framework\App\Action\Context              $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        CheckoutSession                                    $checkoutSession,
        BrmHelper                                          $brmHelper,
        Address                                            $helper,
        \Magento\Framework\Controller\ResultFactory $resultFactory
    )
    {
        $this->helper = $helper;
        $this->storeManager = $storeManager;
        $this->_coreSession = $coreSession;
        $this->_checkoutSession = $checkoutSession;
        $this->brmHelper = $brmHelper;
        $this->_scopeConfig = $scopeConfig;
        $this->resultFactory = $resultFactory;
    }

    public function execute(Observer $observer)
    {
        if (!$this->_coreSession->getCountry()) {
            $country = $this->helper->getGeoIpData();
            if (isset($country['currency_code'])) {
                $this->storeManager->getStore()->setCurrentCurrencyCode($country['currency_code']);
            

            $allCountry = $this->_scopeConfig->getValue("general/country/destinations",
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $storeManagerDataList = $this->storeManager->getStores();

            foreach ($storeManagerDataList as $store) {
                $storeCountry = $this->_scopeConfig->getValue("general/country/destinations",
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store->getId());
                $storeCountries = explode(',', $storeCountry);

                if (in_array($country['country_id'], $storeCountries)) {
                    $url = $this->_scopeConfig->getValue("web/secure/base_url",
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store->getId());
                    $quote = $this->_checkoutSession->getQuote();
                    $quote->getShippingAddress()->setCountryId($country['country_id'])->save();
                    $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                    $resultRedirect->setUrl($url);
                    return $resultRedirect;

                } else {
                    $url = $this->_scopeConfig->getValue("web/secure/base_url",
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE, 1);
                    $quote = $this->_checkoutSession->getQuote();
                    $quote->getShippingAddress()->setCountryId($country['country_id'])->save();
                    $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                    $resultRedirect->setUrl($url);
                    return $resultRedirect;
                }
            }
        }
        }
    }

}