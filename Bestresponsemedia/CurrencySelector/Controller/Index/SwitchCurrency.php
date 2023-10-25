<?php
namespace BestResponseMedia\CurrencySelector\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\RedirectFactory;

class SwitchCurrency extends \Magento\Framework\App\Action\Action
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
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
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlInterface;

    /**
     * @var \Magento\Framework\App\Response\Http
     */
    protected $response;

    /**
     * @var RedirectFactory
     */
    protected $redirectFactory;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        SessionManagerInterface $sessionManager,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Framework\App\Response\Http $response,
        RedirectFactory $redirectFactory
    ) {
        $this->storeManager = $storeManager;
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->sessionManager = $sessionManager;
        $this->urlInterface = $urlInterface;
        $this->httpContext = $httpContext;
        $this->response = $response;
        $this->redirectFactory =$redirectFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $currencyCode = $this->getRequest()->getParam('currency');

        // Get the website based on the currency code
        $website = $this->getWebsiteByCurrencyCode($currencyCode);
        $currentUrl = $this->urlInterface->getCurrentUrl();

        if ($website) {
            // Get the store associated with the selected currency
            $store = $this->getStoreByCurrencyCode($currencyCode);
            if ($store && $store->getIsActive()) {

                $this->storeManager->setCurrentStore($store);
                $this->setCurrencyCookie($currencyCode);
//                $redirectUrl = $this->storeManager->getStore()->getBaseUrl();
                $redirectUrl = $store->getBaseUrl() . '?' . http_build_query(['___store' => $website->getCode()]);

                $response = [
                    'success' => true,
                    'redirectUrl' => $redirectUrl
                ];

            } else {
                $response = ['error' => 'Store not found for the specified currency.'];
            }
        } else {
            $response = ['error' => 'Website not found for the specified currency.'];
        }

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($response));
    }

    private function getWebsiteByCurrencyCode($currencyCode)
    {
        $websites = $this->storeManager->getWebsites();
        foreach ($websites as $website) {
            $defaultStore = $website->getDefaultStore();
            if ($defaultStore && $defaultStore->getCurrentCurrencyCode() === $currencyCode) {
                return $website;
            }
        }
        return null;
    }

    private function getStoreByCurrencyCode($currencyCode)
    {
        $stores = $this->storeManager->getStores();
        foreach ($stores as $store) {
            if ($store->getCurrentCurrencyCode() === $currencyCode) {
                return $store;
            }
        }
        return null;
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
}
