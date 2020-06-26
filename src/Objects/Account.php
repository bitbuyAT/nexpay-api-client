<?php

namespace bitbuyAT\Globitex\Objects;

class Account
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
     * Get Account number.
     *
     * @return string
     */
    public function accountNumber(): string
    {
        return $this->data['account'];
    }

    /**
     * Is this default account for client
     *
     * @return bool
     */
    public function main(): bool
    {
        return $this->data['main'];
    }

    /**
     * Get Balance array.
     *
     * @return BalancesCollection
     */
    public function getBalance(): BalancesCollection
    {
        return (new BalancesCollection($this->data['balance']))->map(function ($data) {
            return new Balance($data);
        });
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
