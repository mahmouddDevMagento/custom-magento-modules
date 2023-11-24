<?php
Namespace UnionCoop\MagentoTask\Cron;

use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use UnionCoop\MagentoTask\Model\ResourceModel\UnioncoopTable\CollectionFactory;
use Psr\Log\LoggerInterface;

class NotifyCustomerCron {

    protected $productFactory;
    protected $transportBuilder;
    protected $storeManager;
    protected $unioncoopTableCollectionFactory;
    protected $logger;

    public function __construct(
        ProductFactory $productFactory,
        CollectionFactory $unioncoopTableCollectionFactory,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        $this->productFactory = $productFactory;
        $this->unioncoopTableCollectionFactory = $unioncoopTableCollectionFactory;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    public function execute()
    {
        try {
            // Get products from unioncoop_table
            $collection = $this->unioncoopTableCollectionFactory->create();
            $collection->addFieldToSelect('*');

            foreach ($collection as $item) {
                $productId = $item->getProductId();
                $product = $this->productFactory->create()->load($productId);

                // Check product if available
                if ($product->isSalable()) {
                    $customerId = $item->getCustomerId();
                    $this->sendNotification($customerId, $product);
                }
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }


    protected function sendNotification($customerId,$product)
    {
        $productName = $product->getName();
        $productSku = $product->getSku();

        $storeId = $this->storeManager->getStore()->getId();
        $templateOptions = [
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => $storeId,
        ];

        $templateVars = [
            'product_name' => $productName,
            'product_sku' => $productSku,
        ];

        $from = ['email' => 'unioncoop@example.com', 'name' => 'unioncoop'];
        $to = ['email' => 'recipient@example.com', 'name' => 'Recipient Name'];
        $transport = $this->transportBuilder->setTemplateIdentifier('unioncoop_template')
            ->setTemplateOptions($templateOptions)
            ->setTemplateVars($templateVars)
            ->setFrom($from)
            ->addTo($to['email'], $to['name'])
            ->getTransport();

        $transport->sendMessage();
    }
}
