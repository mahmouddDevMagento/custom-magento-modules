<?php

namespace BraveBison\Gtag\Block;

use Magento\Checkout\Model\Session;
use Magento\Framework\View\Element\Template\Context;
use Psr\Log\LoggerInterface;

class RemoveFromCart extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Session
     */
    protected $_checkoutSession;

    /**
     * @param Context $context
     * @param Session $checkoutSession
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        Session $checkoutSession,
        LoggerInterface $logger,
        array $data = []
    ) {
        $this->_checkoutSession  = $checkoutSession;
        $this->logger = $logger;
        parent::__construct($context, $data);
    }

    /**
     * Get GTAG checkout session
     *
     * @return Session
     */
    public function getSessionManager()
    {
        return $this->_checkoutSession;
    }
    
    /**
     * Get RemoveFromCartData from checkout session
     */
    public function getRemoveFromCartData()
    {
        $data = [];
        // event RemoveFromCart in Brave Bison module
        if ($this->getSessionManager()->getGTAGRemoveFromCartData()) {
            $data =$this->encodeJs($this->getSessionManager()->getGTAGRemoveFromCartData());
            $this->getSessionManager()->unsGTAGRemoveFromCartData();
        }


        return $data;
    }

    /**
     * Remove RemoveFromCartData from checkout session
     */
    public function removeRemoveFromCartData()
    {
        $this->getSessionManager()->setGTAGRemoveFromCartData(null);
    }

    /**
     * @param $data
     * @return string
     */
    public function encodeJs($data)
    {
        $json = json_encode($data);

        return $json;
    }
}
