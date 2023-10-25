<?php
namespace BestResponseMedia\CurrencySelector\Block;

use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Session\SessionManagerInterface;

class CurrencySelect extends \Magento\Framework\View\Element\Template
{
    private $cookieManager;
    private $cookieMetadataFactory;
    protected $sessionManager;

    /**
     * @var CurrencyFactory
     */
    protected $currencyFactory;

    /**
     * @var \Magento\Framework\Locale\CurrencyInterface
     */
    protected $localecurrency;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        CurrencyFactory $currencyFactory,
        \Magento\Framework\Locale\CurrencyInterface $localeCurrency,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        SessionManagerInterface $sessionManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->currencyFactory = $currencyFactory;
        $this->localecurrency = $localeCurrency;
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->sessionManager = $sessionManager;

    }


    public function getWebsites()
    {
        return $this->_storeManager->getWebsites();
    }

    public function getCurrentWebsiteId()
    {
        return $this->_storeManager->getWebsite()->getId();
    }

    public function getCurrentWebsite()
    {
        return $this->_storeManager->getWebsite();
    }

    public function getRedirectUrl($_lang)
    {
        $url =$_lang->getDefaultStore()->getCurrentUrl(false);

        // Get the default currency code of the website
        $currencyCode = $this->getCurrentWebsite()->getDefaultStore()->getDefaultCurrencyCode();
        $this->setCurrencyCookie($currencyCode);

        // @phpstan-ignore-next-line
        return $url ;
    }

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

    public function getCurrentStoreCurrencyCode($websiteId)
    {
        $store = $this->getStoreByWebsite($websiteId);
        if ($store) {
            return $store->getCurrentCurrencyCode();
        }
        return '';
    }


    protected function getStoreByWebsite($websiteId)
    {
        $website = $this->_storeManager->getWebsite($websiteId);
        if ($website) {
            $stores = $website->getStores();
            foreach ($stores as $store) {
                if ($store->getIsActive()) {
                    return $store;
                }
            }
        }
        return null;
    }


    /**
     * @param string $currencycode
     * @return string|null
     */
    public function getCurrencySymbol($currencycode)
    {
        return $this->localecurrency->getCurrency($currencycode)->getSymbol();
    }

    public function getAllowedCurrencies()
    {
        return $this->currencyFactory->create()->getConfigAllowCurrencies();
    }
}
