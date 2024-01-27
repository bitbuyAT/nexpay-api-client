<?php

namespace bitbuyAT\Nexpay\Objects;

class Ticker
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
     * Last price.
     */
    public function lastPrice(): float
    {
        return (float) $this->data['last'];
    }

    /**
     * Last 24 hours price high.
     */
    public function highPrice(): float
    {
        return (float) $this->data['high'];
    }

    /**
     * Last 24 hours price low.
     */
    public function lowPrice(): float
    {
        return (float) $this->data['low'];
    }

    /**
     * Last 24 hours volume.
     */
    public function volume(): float
    {
        return (float) $this->data['volume'];
    }

    /**
     * Trade volume in second currency per last 24h + last incomplete minute.
     */
    public function volumeQuote(): float
    {
        return (float) $this->data['volumeQuote'];
    }

    /**
     * Bid price
     * Highest buy order.
     */
    public function bidPrice(): float
    {
        return (float) $this->data['bid'];
    }

    /**
     * Ask price
     * Lowest sell order.
     */
    public function askPrice(): float
    {
        return (float) $this->data['ask'];
    }

    /**
     * Unix timestamp date and time.
     */
    public function timestamp(): int
    {
        return (int) $this->data['timestamp'];
    }

    /**
     * First price of the day.
     */
    public function openPrice(): float
    {
        return (float) $this->data['open'];
    }

    /**
     * Whole data array.
     */
    public function getData(): array
    {
        return $this->data;
    }
}
