<?php

namespace bitbuyAT\Nexpay\Objects;

class Account
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
     * Get Account number.
     */
    public function accountNumber(): string
    {
        return $this->data['account'];
    }

    /**
     * Is this default account for client.
     */
    public function main(): bool
    {
        return $this->data['main'];
    }

    /**
     * Get Balance array.
     */
    public function getBalance(): BalancesCollection
    {
        return (new BalancesCollection($this->data['balance']))->map(function ($data) {
            return new Balance($data);
        });
    }

    /**
     * Whole data array.
     */
    public function getData(): array
    {
        return $this->data;
    }
}
