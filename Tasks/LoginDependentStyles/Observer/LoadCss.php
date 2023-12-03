<?php
namespace Tasks\LoginDependentStyles\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Page\Config;

class LoadCss implements ObserverInterface
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var Config
     */
    protected $pageConfig;

    /**
     * @param CustomerSession $customerSession
     * @param Config $pageConfig
     */
    public function __construct(
        CustomerSession $customerSession,
        Config $pageConfig
    ) {
        $this->customerSession = $customerSession;
        $this->pageConfig = $pageConfig;
    }

    public function execute(Observer $observer)
    {
        // Check if customer is logged in
        if ($this->customerSession->isLoggedIn()) {
            $this->pageConfig->addPageAsset('Tasks_LoginDependentStyles::css/custom.css');
        }
    }
}
