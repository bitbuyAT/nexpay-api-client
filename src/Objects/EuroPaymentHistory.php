<?php

namespace bitbuyAT\Globitex\Objects;

class EuroPaymentHistory
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
     * Get Debit turnover balance.
     *
     * @return string
     */
    public function debitTurnover(): string
    {
        return $this->data['debitTurnover'];
    }

    /**
     * Get Credit turnover balance.
     *
     * @return string
     */
    public function creditTurnover(): string
    {
        return $this->data['creditTurnover'];
    }

    /**
     * Get Balance start amount.
     *
     * @return string
     */
    public function balanceStart(): string
    {
        return $this->data['balanceStart'];
    }

    /**
     * Get Balance end amount.
     *
     * @return string
     */
    public function balanceEnd(): string
    {
        return $this->data['balanceEnd'];
    }

    /**
     * Get Client full name.
     *
     * @return string
     */
    public function clientName(): string
    {
        return $this->data['clientName'];
    }

    /**
     * Get Account holder`s IBAN number.
     *
     * @return string
     */
    public function account(): string
    {
        return $this->data['account'];
    }

    /**
     * Get Array of account transaction entries, see https://globitex.com/api/#GetPaymentHistory.
     *
     * @return array
     */
    public function entries(): array
    {
        return $this->data['entries'];
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
