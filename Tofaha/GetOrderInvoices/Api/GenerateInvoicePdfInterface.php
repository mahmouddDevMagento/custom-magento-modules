<?php

namespace Tofaha\GetOrderInvoices\Api;

interface GenerateInvoicePdfInterface
{
    /**
     * @param int $orderId
     * @return \Tofaha\OperatorApp\DTO\GlobalHeaderDTO | string | int | void
     */
    public function generateInvoicePdf($orderId);
}