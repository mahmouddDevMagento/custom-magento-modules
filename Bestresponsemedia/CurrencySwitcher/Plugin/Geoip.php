<?php

namespace BestResponseMedia\CurrencySwitcher\Plugin;

use BestResponseMedia\CurrencySwitcher\Helper\Address as BrmAddressHelper;

class Geoip
{

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * @var BrmAddressHelper
     */
    protected $brmAddress;

    /**
     * CustomerSessionContext constructor.
     * @param \Magento\Framework\App\Http\Context $httpContext
     */
    public function __construct(
        BrmAddressHelper $brmAddress,
        \Magento\Framework\App\Http\Context $httpContext
    ) {

        $this->httpContext = $httpContext;
        $this->brmAddress = $brmAddress;
    }

    /**
     * @param \Magento\Framework\App\ActionInterface $subject
     * @param callable $proceed
     * @param \Magento\Framework\App\RequestInterface $request
     * @return mixed
     */
    public function aroundDispatch(
        \Magento\Framework\App\ActionInterface $subject,
        \Closure $proceed,
        \Magento\Framework\App\RequestInterface $request
    ) {

        $this->httpContext->setValue(
            'brm_geoip',
            $this->brmAddress->getGeoIpData(),
            false
        );

        return $proceed($request);
    }

}