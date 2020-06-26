<?php

namespace bitbuyAT\Globitex\Objects;

class GBXUtilizationTransaction
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
     * Transaction code on the exchange.
     */
    public function transactionCode(): string
    {
        return $this->data['transactionCode'];
    }

    /**
     * Transaction creation date. (UNIX timestamp in milliseconds since epoch).
     */
    public function created(): int
    {
        return $this->data['created'];
    }

    /**
     * Direction of the payment transaction.
     */
    public function direction(): string
    {
        return $this->data['direction'];
    }

    /**
     * Transaction amount.
     */
    public function amount(): string
    {
        return $this->data['amount'];
    }

    /**
     * Currency symbol, e.g. EUR.
     */
    public function currency(): string
    {
        return $this->data['currency'];
    }

    /**
     * Own account number in the exchange on which the transaction was made.
     */
    public function account(): string
    {
        return $this->data['account'];
    }

    /**
     * Transaction details.
     */
    public function details(): string
    {
        return $this->data['details'];
    }

    /**
     * Discount information. Discount object structure can be seen at https://globitex.com/api/#GbxUtilizationList.
     */
    public function discount(): array
    {
        return $this->data['discount'] ?? [];
    }

    /**
     * Whole data array.
     */
    public function getData(): array
    {
        return $this->data;
    }
}
