<?php
Namespace UnionCoop\MagentoTask\Cron;

use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use UnionCoop\MagentoTask\Model\ResourceModel\UnioncoopTable\CollectionFactory;
use Psr\Log\LoggerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

class NotifyCustomerCron {

    /**
     * @var ProductFactory
     */
    protected $productFactory;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var CollectionFactory
     */
    protected $unioncoopTableCollectionFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @param ProductFactory $productFactory
     * @param CollectionFactory $unioncoopTableCollectionFactory
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        ProductFactory $productFactory,
        CollectionFactory $unioncoopTableCollectionFactory,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->productFactory = $productFactory;
        $this->unioncoopTableCollectionFactory = $unioncoopTableCollectionFactory;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->customerRepository = $customerRepository;
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


    protected function sendNotification($customerId, $product)
    {
        $productName = $product->getName();
        $productSku = $product->getSku();

        // Get customer email by ID
        $customer = $this->customerRepository->getById($customerId);
        $customerEmail = $customer->getEmail();

        $storeId = $this->storeManager->getStore()->getId();
        $templateOptions = [
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => $storeId,
        ];

        $templateVars = [
            'product_name' => $productName,
            'product_sku' => $productSku,
        ];

        $from = ['email' => 'unioncoop@example.com', 'name' => 'UnionCoop'];
        $to = ['email' => $customerEmail, 'name' => $customer->getFirstname()]; // Send email to the customer
        $transport = $this->transportBuilder->setTemplateIdentifier('unioncoop_template')
            ->setTemplateOptions($templateOptions)
            ->setTemplateVars($templateVars)
            ->setFrom($from)
            ->addTo($to['email'], $to['name'])
            ->getTransport();

        $transport->sendMessage();
    }

}
