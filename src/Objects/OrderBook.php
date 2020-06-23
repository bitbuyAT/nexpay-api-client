<?php

namespace bitbuyAT\Globitex\Objects;

class OrderBook
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get Bids prices.
     *
     * @return array
     */
    public function getBids(): array
    {
        return $this->data['bids'];
    }

    /**
     * Get Asks prices.
     *
     * @return array
     */
    public function getAsks(): array
    {
        return $this->data['asks'];
    }

    /**
     * Unix timestamp date and time.
     *
     * @return int
     */
    public function timestamp(): int
    {
        return (int) $this->data['timestamp'];
    }

    /**
     * Whole data array.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
