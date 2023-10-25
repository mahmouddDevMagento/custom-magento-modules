<?php

namespace Tofaha\GetOrderInvoices\Model;

use Tofaha\GetOrderInvoices\Api\GenerateInvoicePdfInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Registry;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Model\OrderRepository;
use Tofaha\OperatorApp\DTO\GlobalResponse;
use Tofaha\OperatorApp\DTO\GlobalHeaderDTO;
use Tofaha\GetOrderInvoices\DTO\InvoiceDetailsDTO;
use Tofaha\GetOrderInvoices\DTO\InvoiceDetailsBodyDTO;
use Magento\Framework\Api\SearchCriteriaBuilder;



class GenerateInvoicePdf implements GenerateInvoicePdfInterface
{
    /**
     * @var \MageArab\PrintPdf\Model\InvoicePdf
     */
    private $_invoicePdf;

    /**
     * @var InvoiceRepositoryInterface
     */
    private $_invoiceRepository;

    /**
     * @var \Magento\Sales\Model\Order
     */
    private $_orderModel;

    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * Abstractpdf constructor.
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        \MageArab\PrintPdf\Model\InvoicePdf                         $invoicePdf,
        InvoiceRepositoryInterface                                  $invoiceRepository,
        \Magento\Sales\Model\Order                                  $orderModel,
        JsonFactory                                                 $resultJsonFactory,
        OrderRepository $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {

        $this->_invoicePdf                                  =   $invoicePdf;
        $this->_orderModel                                  =   $orderModel;
        $this->resultJsonFactory                            =   $resultJsonFactory;
        $this->_invoiceRepository                           =   $invoiceRepository;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;


    }

    public function generateInvoicePdf($orderId)
    {
        $order = $this->orderRepository->get($orderId);
        if($order->hasInvoices()){
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter('order_id', $orderId)->create();
            try {
                $invoices = $this->_invoiceRepository->getList($searchCriteria);
                $invoiceId = '';
                foreach ($invoices as $invoice) {
                    $invoiceStatus = $invoice->getState();
//                        echo $invoiceStatus ;die('ddd');

                    if ($invoiceStatus == \Magento\Sales\Model\Order\Invoice::STATE_PAID || $invoiceStatus == \Magento\Sales\Model\Order\Invoice::STATE_OPEN) {
                        $invoiceId = $invoice->getId();
                        $this->_invoicePdf->_pdf=new \MageArab\PrintPdf\Model\Pdf ($this->_invoicePdf->_helperData,$this->_invoicePdf->_storeManager,$this->_invoicePdf->_filterProvider,'A7');
                        $invoice = $this->_invoiceRepository->get($invoiceId);
                        $order=$this->_orderModel->load($invoice->getOrderId());
                        $this->_invoicePdf->createInvoice($invoice,$order);
                        $this->_invoicePdf->_pdf->Output('invoice_' . date('m-d-Y_hia') . '.pdf', 'D');

                    } else {
                        return new GlobalHeaderDTO(0, false, 'this order not hase any paid or open invoice.', 'الطلب ليس لديه اي فاتوره مدفوعه.');
                    }
                }

            } catch (Exception $exception)  {
                $this->logger->critical($exception->getMessage());
            }
        }else{
            return new GlobalHeaderDTO(0, false, 'this order not not any invoices.', 'هذا الطلب ليس لديه اي فاتوره.');

        }

//        try {
//            $this->_invoicePdf->_pdf=new \MageArab\PrintPdf\Model\Pdf ($this->_invoicePdf->_helperData,$this->_invoicePdf->_storeManager,$this->_invoicePdf->_filterProvider,'A7');
//            $invoice = $this->_invoiceRepository->get($invoiceId);
//            $order=$this->_orderModel->load($invoice->getOrderId());
//            $this->_invoicePdf->createInvoice($invoice,$order);
//            $this->_invoicePdf->_pdf->Output('invoice_' . date('m-d-Y_hia') . '.pdf', 'D');
////            $this->_invoicePdf->_pdf->Output(__DIR__ . '/example_001.pdf', 'F');
////            $sFilePath = $_SERVER['DOCUMENT_ROOT'] . 'file.pdf' ;
////            $this->_invoicePdf->_pdf->Output( $sFilePath , 'F');
//
//            return;
//        }catch (\Exception $e){
//            return $e->getMessage();
//        }
    }
}