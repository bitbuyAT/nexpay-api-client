<?php

namespace bitbuyAT\Globitex\Objects;

class OrderBook
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
     * Get Bids prices.
     */
    public function bids(): array
    {
        return $this->data['bids'];
    }

    /**
     * Get Asks prices.
     */
    public function asks(): array
    {
        return $this->data['asks'];
    }

    /**
     * Whole data array.
     */
    public function getData(): array
    {
        return $this->data;
    }
}
