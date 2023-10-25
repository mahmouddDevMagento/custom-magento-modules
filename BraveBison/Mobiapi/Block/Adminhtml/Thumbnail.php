<?php

namespace BraveBison\Mobiapi\Block\Adminhtml;


class Thumbnail extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * Function render
     *
     * @param \Magento\Framework\DataObject $row row
     *
     * @return html
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get(\Magento\Store\Model\StoreManagerInterface::class);
        $target = $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $imageUrl = $target.'wysiwyg/mobiapi/'.$row->getImage();
        $html  = '<img style="border:1px solid #d6d6d6;width:50px" src="'.$imageUrl.'"/>';
        return $html;
    }
}
