<?php

namespace Tofaha\GetOrderInvoices\Model;

use Tofaha\GetOrderInvoices\Api\OrderInvoicesInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Model\OrderRepository;
use Tofaha\OperatorApp\DTO\GlobalResponse;
use Tofaha\OperatorApp\DTO\GlobalHeaderDTO;
use Tofaha\GetOrderInvoices\DTO\InvoiceDetailsDTO;
use Tofaha\GetOrderInvoices\DTO\InvoiceDetailsBodyDTO;

class OrderInvoices implements OrderInvoicesInterface
{
    /**
     * @var InvoiceRepositoryInterface
     */
    private $invoiceRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * @var Metadata
     */
    protected $metadata;

    /**
     * \Magento\Sales\Api\Data\InvoiceInterface[]
     *
     * @var array
     */
    protected $registry = [];

    /**
     * @var \Magento\Sales\Model\Order\Invoice
     */
    protected $invoice;

    /**
     * @param InvoiceRepositoryInterface $invoiceRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param LoggerInterface $logger
     * @param OrderRepository $orderRepository
     */
    public function __construct(
        InvoiceRepositoryInterface $invoiceRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        LoggerInterface $logger,
        OrderRepository $orderRepository,
        \Magento\Sales\Model\Order\Invoice $invoice
    ) {
        $this->invoiceRepository = $invoiceRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->logger = $logger;
        $this->orderRepository = $orderRepository;
        $this->invoice = $invoice;

    }

    /**
     * @param int $orderId
     * @return \Tofaha\OperatorApp\DTO\InvoiceDetailsDTO
     */
   public function getOrderInvoices($orderId)
   {
       $order = $this->orderRepository->get($orderId);

       if($order->hasInvoices()){
           $searchCriteria = $this->searchCriteriaBuilder
               ->addFilter('order_id', $orderId)->create();
           try {
               $invoices = $this->invoiceRepository->getList($searchCriteria);
               $ids = [];
               foreach ($invoices as $invoice) {
                   $ids[]= $invoice->getId();
               }
               $invoiceDetailsBody = new InvoiceDetailsBodyDTO(
                   $ids
               );
               return new InvoiceDetailsDTO(
                   new GlobalHeaderDTO(1, true, 'Order has invoices.', 'الطلب بالفعل لديه فاتوره.'),
                   $invoiceDetailsBody
               );
           } catch (Exception $exception)  {
               $this->logger->critical($exception->getMessage());
           }

       } else {
           return new InvoiceDetailsDTO(new GlobalHeaderDTO(999, false, 'Order has no invoices .', 'الطلب بالفعل ليس لديه اي فاتوره.'), null);
       }
   }
}