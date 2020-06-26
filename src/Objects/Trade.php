<?php

namespace bitbuyAT\Globitex\Objects;

class Trade
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
     * Unix timestamp date and time.
     */
    public function date(): int
    {
        return $this->data['date'];
    }

    /**
     * Trade ID.
     */
    public function tradeId(): int
    {
        return $this->data['tid'];
    }

    /**
     * Price.
     */
    public function price(): float
    {
        return (float) $this->data['price'];
    }

    /**
     * Amount.
     */
    public function amount(): float
    {
        return (float) $this->data['amount'];
    }

    /**
     * Whole data array.
     */
    public function getData(): array
    {
        return $this->data;
    }
}
