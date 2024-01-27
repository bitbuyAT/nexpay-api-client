<?php

namespace bitbuyAT\Nexpay\Objects;

class EuroPaymentHistory
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
     * Get Debit turnover balance.
     */
    public function debitTurnover(): string
    {
        return $this->data['debitTurnover'];
    }

    /**
     * Get Credit turnover balance.
     */
    public function creditTurnover(): string
    {
        return $this->data['creditTurnover'];
    }

    /**
     * Get Balance start amount.
     */
    public function balanceStart(): string
    {
        return $this->data['balanceStart'];
    }

    /**
     * Get Balance end amount.
     */
    public function balanceEnd(): string
    {
        return $this->data['balanceEnd'];
    }

    /**
     * Get Client full name.
     */
    public function clientName(): string
    {
        return $this->data['clientName'];
    }

    /**
     * Get Account holder`s IBAN number.
     */
    public function account(): string
    {
        return $this->data['account'];
    }

    /**
     * Get Array of account transaction entries, see https://paynexpay.com/api/#get-account-history.
     */
    public function entries(): array
    {
        return $this->data['entries'];
    }

    /**
     * Whole data array.
     */
    public function getData(): array
    {
        return $this->data;
    }
}
