<?php

namespace Tofaha\GetOrderInvoices\DTO;

class InvoiceDetailsDTO
{
    private $header;
    private $body;

    /**
     * OrderDetailsDTO constructor.
     * @param \Tofaha\OperatorApp\DTO\GlobalHeaderDTO $header
     * @param \Tofaha\GetOrderInvoices\DTO\InvoiceDetailsBodyDTO $body
     */
    public function __construct($header, $body)
    {
        $this->header = $header;
        $this->body = $body;
    }

    /**
     * @return \Tofaha\OperatorApp\DTO\GlobalHeaderDTO
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param \Tofaha\OperatorApp\DTO\GlobalHeaderDTO $header
     * @return void
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * @return \Tofaha\GetOrderInvoices\DTO\InvoiceDetailsBodyDTO
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param \Tofaha\GetOrderInvoices\DTO\InvoiceDetailsBodyDTO $body
     * @return void
     */
    public function setBody($body)
    {
        $this->body = $body;
    }
}
