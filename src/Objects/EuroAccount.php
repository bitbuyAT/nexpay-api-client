<?php

namespace bitbuyAT\Globitex\Objects;

class EuroAccount
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
     * Get IBAN number.
     *
     * @return string
     */
    public function iban(): string
    {
        return $this->data['iban'];
    }

    /**
     * Get IBAN status (ACTIVE/CLOSE).
     *
     * @return string
     */
    public function status(): string
    {
        return $this->data['status'];
    }

    /**
     * Get account balance
     *
     * @return string
     */
    public function balance(): string
    {
        return $this->data['balance'];
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
