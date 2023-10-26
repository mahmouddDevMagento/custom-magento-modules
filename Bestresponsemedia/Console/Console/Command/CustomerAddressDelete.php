<?php

namespace BestResponseMedia\Console\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Input\InputArgument;


class CustomerAddressDelete extends Command
{
    /**
     * Logging instance
     * @var \BestResponseMedia\Console\Model\Logger\DeletedCustomerAddressesLogger
     */
    protected $logger;

    /** @var \Magento\Customer\Model\CustomerFactory */
    protected $customerFactory;

    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $customer;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Customer\Api\AddressRepositoryInterface
     */
    protected $addressRepository;


    /**
     * Link constructor.
     *
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     * @param \Magento\Store\Model\StoreManagerInterfac $storeManager
     * @param \Magento\Customer\Model\Customer $customer
     * @param \Magento\Customer\Api\AddressRepositoryInterface $addressRepository
     */
    public function __construct(
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \BestResponseMedia\Console\Model\Logger\DeletedCustomerAddressesLogger $logger
    )
    {
        $this->customerFactory = $customerFactory;
        $this->storeManager = $storeManager;
        $this->customer = $customer;
        $this->addressRepository = $addressRepository;
        $this->logger = $logger;
        parent::__construct();
    }


    protected function configure()
    {
        $this->setName('bb:delete:customer-address')
            ->setDescription('Delete customer addresses based on street length')
            ->addArgument('pagesize', InputArgument::REQUIRED, 'number of customers to process in one batch)')
            ->addArgument('pagenumber', InputArgument::REQUIRED, 'batch number to process)');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pageSize = (int) $input->getArgument('pagesize');
        $pageNumber = (int) $input->getArgument('pagenumber');

        // get customer collection based on pagesize and pagenumber inputes
        $customerCollection = $this->getCustomerCollection($pageSize, $pageNumber);

        try {
            foreach ($customerCollection as $customer) {
                $customerId = $customer->getId();
                if (!empty($this->getCustomerAddress($customerId))) {
                    $customerAddressList = $this->getCustomerAddress($customerId);
                    foreach ($customerAddressList as $address) {
                        if ($address['entity_id']) {
                            $this->addressRepository->deleteById($address['entity_id']);
//                            $output->writeln('address # ' . $address['entity_id'] . ' has been deleted for # ' . $customerId);
                            $this->logger->info('address # ' . $address['entity_id'] . ' has been deleted for # ' . $customerId);
                        }

                    }
                } else {
//                    $output->writeln('<info>customer addresses all are applicable for current rule.</info>');
                    $this->logger->info('customer addresses all are applicable for current rule.');
                }
            }
        } catch
        (\Exception $e) {
//            $output->writeln('<error>' . $e->getMessage() . '</error>');
            $this->logger->info('Errors: ' . $e->getMessage());
        }
        $output->writeln('<info>customer addresses check has been completed.</info>');
        $this->logger->info('customer addresses check has been completed.');
        return Cli::RETURN_SUCCESS;
    }

    /**
     * Get customer collection
     *
     * @return array
     */
    public function getCustomerCollection($pageSize=1000, $pageNumber = 1)
    {
//        return $this->customer->getCollection()
//            ->addAttributeToSelect("*")
//            ->load();

        return $this->customer->getCollection()
            ->addAttributeToSelect("*")
            ->setPageSize($pageSize)
            ->setCurPage($pageNumber)
            ->load();
    }


    /**
     * Get customer collection
     *
     * @return array
     */

    public function getCustomerAddress($customerId)
    {
        $customer = $this->customerFactory->create();
        $websiteId = $this->storeManager->getStore()->getWebsiteId();
        $customer->setWebsiteId($websiteId);
        $customerModel = $customer->load($customerId);

        $customerAddressData = [];
        $customerAddress = [];

        if ($customerModel->getAddresses() != null) {
            foreach ($customerModel->getAddresses() as $address) {
                $customerAddress[] = $address->toArray();
            }
        }

        if ($customerAddress != null) {
            foreach ($customerAddress as $customerAddress) {
                $street = $customerAddress['street'];
                //check if rule applied for the street
                if (strlen($street) > 32 && strpos($street, 'REQUEST') === false) {
                    $customerAddressData[] = $customerAddress;
                }
            }
        }
        return $customerAddressData;
    }

}
