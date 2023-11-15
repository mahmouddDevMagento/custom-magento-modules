<?php

namespace Magebit\PageListWidget\Model\Config\Source;

use Magento\Cms\Model\Page;
use Magento\Framework\Option\ArrayInterface;

/**
 * Class PageList
 * @package Magebit\PageListWidget\Model\Config\Source
 */
class PageList implements ArrayInterface
{
    /**
     * @var Page
     */
    protected $pageModel;

    /**
     * PageList constructor.
     * @param Page $pageModel
     */
    public function __construct(
        Page $pageModel
    ) {
        $this->pageModel = $pageModel;
    }

    /**
     * Get options array
     * @return array
     */
    public function toOptionArray()
    {
        $collection = $this->pageModel->getCollection();
        $options = [];

        foreach ($collection as $page) {
            $options[] = [
                'value' => $page->getId(),
                'label' => $page->getTitle()
            ];
        }

        return $options;
    }
}
