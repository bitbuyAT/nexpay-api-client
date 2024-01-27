<?php

namespace bitbuyAT\Nexpay\Objects;

class EuroAccount
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
     * Get IBAN number.
     */
    public function iban(): string
    {
        return $this->data['iban'];
    }

    /**
     * Get IBAN status (ACTIVE/CLOSE).
     */
    public function status(): string
    {
        return $this->data['status'];
    }

    /**
     * Get account balance.
     */
    public function balance(): string
    {
        return $this->data['balance'];
    }

    /**
     * Whole data array.
     */
    public function getData(): array
    {
        return $this->data;
    }
}
