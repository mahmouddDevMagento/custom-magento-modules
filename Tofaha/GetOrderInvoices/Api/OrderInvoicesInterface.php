<?php
 
namespace Tofaha\GetOrderInvoices\Api;

interface OrderInvoicesInterface
{
    /**
     * @param int $orderId
     * @return \Tofaha\GetOrderInvoices\DTO\InvoiceDetailsDTO
     */
     public function getOrderInvoices($orderId);
}