<?php

namespace Tofaha\AddThumbnailForOrderedItems\Plugin\Block\Sales\Adminhtml\Order\View\Items;

use Magento\Backend\Block\Template;

/**
 * Class BundleRenderer
 */
class BundleRenderer
{
    /**
     * @param Template $originalBlock
     */
    public function beforeToHtml(Template $originalBlock)
    {
        $originalBlock->setTemplate('Tofaha_AddThumbnailForOrderedItems::order/view/items/renderer/bundle.phtml');
    }
}
