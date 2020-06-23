<?php

namespace bitbuyAT\Globitex\Objects;

class Transaction
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
     * Transaction ID.
     *
     * @return int
     */
    public function getTransactionId(): int
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
     * Type
     * 0 (buy) or 1 (sell).
     *
     * @return int
     */
    public function type(): int
    {
        return (int) $this->data['type'];
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
