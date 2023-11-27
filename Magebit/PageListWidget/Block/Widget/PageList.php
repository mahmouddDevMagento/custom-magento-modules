<?php
namespace Magebit\PageListWidget\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Cms\Model\PageFactory;
use Magento\Cms\Model\ResourceModel\Page\CollectionFactory as PageCollectionFactory;

class PageList extends Template implements BlockInterface
{
    const OPTION_ALL_PAGES = '1';
    const OPTION_SPECIFIC_PAGES = '2';

    protected $_template = "widget/pagelist.phtml";

    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @var PageCollectionFactory
     */
    protected $pageCollectionFactory;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @param Template\Context $context
     * @param PageFactory $pageFactory
     * @param PageCollectionFactory $pageCollectionFactory
     * @param \Magento\Framework\Escaper $escaper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        PageFactory $pageFactory,
        PageCollectionFactory $pageCollectionFactory,
        \Magento\Framework\Escaper $escaper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->pageFactory = $pageFactory;
        $this->pageCollectionFactory = $pageCollectionFactory;
        $this->escaper = $escaper;
    }

    public function getTitle(): ?string
    {
        $title = $this->getData('title');
        return $this->escaper->escapeHtml($title);
    }

    public function getCmsPages()
    {
        $displayMode = $this->getData('displaymode');

        if ($displayMode === self::OPTION_ALL_PAGES) {
            return $this->getAllPages();
        } elseif ($displayMode === self::OPTION_SPECIFIC_PAGES) {
            return $this->getSpecificPages();
        }

        return $this->getDefaultPages();
    }

    protected function getAllPages()
    {
        $pageCollection = $this->pageCollectionFactory->create()->addFieldToFilter('is_active', 1);;
        $pageCollection->addOrder('page_id', 'ASC');
        return $pageCollection;
    }

    protected function getSpecificPages()
    {
        $selectedPageIds = $this->getData('selectedpages');
        if ($selectedPageIds) {
            $selectedPageIds = explode(',', $selectedPageIds);
            if (!empty($selectedPageIds)) {
                $pageCollection = $this->pageCollectionFactory->create();
                $pageCollection->addFieldToFilter('page_id', ['in' => $selectedPageIds])
                    ->addFieldToFilter('is_active', 1);;
                return $pageCollection;
            }
        }
        return $this->getDefaultPages();
    }

    protected function getDefaultPages()
    {
        return $this->pageCollectionFactory->create();
    }
}
