<?php

namespace Tofaha\GetOrderInvoices\DTO;

class InvoiceDetailsBodyDTO
{
    private $invoicesIds = [];

    public function __construct(
        array $invoicesIds
    )
    {
        foreach($invoicesIds as $invoiceId) {
            $this->invoicesIds[] = $invoiceId;
        }
    }

    /**
     * @return array
     */
    public function getInvoicesIds(): array
    {
        return $this->invoicesIds;
    }

    /**
     * @param array $invoicesIds
     * @return void
     */
    public function setInvoicesIds(array $invoicesIds): void
    {
        $this->invoicesIds = $invoicesIds;
    }
}
