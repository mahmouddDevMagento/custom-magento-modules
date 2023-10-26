<?php

namespace BestResponseMedia\IntercomChat\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Customer\Model\Session as CustomerSession;


class Data extends AbstractHelper
{
    /**
     * @var  CustomerSession
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * @param CustomerSession $customerSession
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param Context $context
     */
    public function __construct(
        CustomerSession $customerSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Http\Context $httpContext,
        Context $context
    )
    {
        $this->customerSession = $customerSession;
        $this->scopeConfig = $scopeConfig;
        $this->httpContext = $httpContext;
        parent::__construct($context);
    }
    public function IsLoggedIn(){
//        if ($this->customerSession->isLoggedIn()) {
//            return true;
//        }else{
//            return  false;
//        }
//
//        return false;
//        return $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
        return (bool)$this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);

    }

    public function getCustomerName()
    {
        return $this->httpContext->getValue('customer_name');
    }

    public function getCustomerEmail()
    {
        return $this->httpContext->getValue('customer_email');
    }


    public function getCustomerCreatedAt()
    {
        return $this->httpContext->getValue('created_at');
    }
    
    public function getAppId(){
      $appId = $this->scopeConfig->getValue('brm_chat/general/app_id',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

      return $appId;
    }
}
