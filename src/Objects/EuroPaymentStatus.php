<?php

namespace bitbuyAT\Globitex\Objects;

class EuroPaymentStatus
{
    /**
     * @var array
     */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get Debit turnover balance.
     */
    public function paymentId(): string
    {
        return $this->data['paymentId'];
    }

    /**
     * Get Credit turnover balance.
     */
    public function status(): string
    {
        return $this->data['status'];
    }

    /**
     * Whole data array.
     */
    public function getData(): array
    {
        return $this->data;
    }
}
