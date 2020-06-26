<?php

namespace bitbuyAT\Globitex\Objects;

class Trade
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
     * Unix timestamp date and time.
     *
     * @return int
     */
    public function getDate(): int
    {
        return $this->data['date'];
    }

    /**
     * Trade ID.
     *
     * @return int
     */
    public function getTradeId(): int
    {
        return $this->data['tid'];
    }

    /**
     * Price.
     *
     * @return float
     */
    public function price(): float
    {
        return (float) $this->data['price'];
    }

    /**
     * Amount.
     *
     * @return float
     */
    public function amount(): float
    {
        return (float) $this->data['amount'];
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
