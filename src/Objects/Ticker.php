<?php

namespace bitbuyAT\Globitex\Objects;

class Ticker
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
     * Last price.
     *
     * @return float
     */
    public function lastPrice(): float
    {
        return (float) $this->data['last'];
    }

    /**
     * Last 24 hours price high.
     *
     * @return float
     */
    public function highPrice(): float
    {
        return (float) $this->data['high'];
    }

    /**
     * Last 24 hours price low.
     *
     * @return float
     */
    public function lowPrice(): float
    {
        return (float) $this->data['low'];
    }

    /**
     * Last 24 hours volume.
     *
     * @return float
     */
    public function volume(): float
    {
        return (float) $this->data['volume'];
    }

     /**
     * Trade volume in second currency per last 24h + last incomplete minute
     *
     * @return float
     */
    public function volumeQuote(): float
    {
        return (float) $this->data['volumeQuote'];
    }

    /**
     * Bid price
     * Highest buy order.
     *
     * @return float
     */
    public function bidPrice(): float
    {
        return (float) $this->data['bid'];
    }

    /**
     * Ask price
     * Lowest sell order.
     *
     * @return float
     */
    public function askPrice(): float
    {
        return (float) $this->data['ask'];
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
     * First price of the day.
     *
     * @return float
     */
    public function openPrice(): float
    {
        return (float) $this->data['open'];
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
