<?php

namespace bitbuyAT\Nexpay\Objects;

class Balance
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
     * Get Currency symbol, e.g. EUR.
     */
    public function currency(): string
    {
        return $this->data['currency'];
    }

    /**
     * Get currency amount available for trading or payments.
     */
    public function available(): string
    {
        return $this->data['available'];
    }

    /**
     * Currency amount reserved for active orders.
     */
    public function reserved(): string
    {
        return $this->data['reserved'];
    }

    /**
     * Whole data array.
     */
    public function getData(): array
    {
        return $this->data;
    }
}
