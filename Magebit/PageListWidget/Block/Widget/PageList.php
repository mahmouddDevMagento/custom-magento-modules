<?php
namespace Magebit\PageListWidget\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Cms\Model\PageFactory;

class PageList extends Template implements BlockInterface
{
    const OPTION_ALL_PAGES = '1';
    const OPTION_SPECIFIC_PAGES = '2';

    /**
     * @var string
     */
    protected $_template = "widget/pagelist.phtml";

    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * PageList constructor.
     *
     * @param Template\Context $context
     * @param PageFactory $pageFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        PageFactory $pageFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->pageFactory = $pageFactory;
    }

    /**
     * Get the title set for the widget.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->getData('title');
    }

    /**
     * Get the collection of CMS pages based on the selected display mode.
     *
     * @return \Magento\Cms\Model\ResourceModel\Page\Collection
     */
    public function getCmsPages()
    {
        $displayMode = $this->getData('displaymode');

        if ($displayMode === self::OPTION_ALL_PAGES) {
            $pageCollection = $this->pageFactory->create()->getCollection();
            return $pageCollection;
        } elseif ($displayMode === self::OPTION_SPECIFIC_PAGES) {
            $selectedPageIds = explode(',', $this->getData('selectedpages'));
            if (!empty($selectedPageIds)) {
                $pageCollection = $this->pageFactory->create()->getCollection()
                    ->addFieldToFilter('page_id', ['in' => $selectedPageIds]);
                return $pageCollection;
            }
        }

        return $this->pageFactory->create()->getCollection();
    }
}
