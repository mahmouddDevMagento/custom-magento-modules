<?php

namespace BestResponseMedia\MultishippingCustomization\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    /**
     * Get Helper context object
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $url;

    /**
     * Assign object to param
     * @param \Magento\Framework\UrlInterface $url
     */
    public function __construct(
        \Magento\Framework\UrlInterface $url

    ) {
        $this->url = $url;
    }
    /**
     * @param mixed $item
     * @return string
     */
    public function getItemSplitUrl($item)
    {
        return $this->url->getUrl('multishippingcustomization/index/removeItem', ['address' => $item->getQuoteAddressId(), 'id' => $item->getId()]);
    }
}
