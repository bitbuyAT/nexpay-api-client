<?php

namespace bitbuyAT\Globitex\Objects;

class UserTransaction
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
     * @return string
     */
    public function getDateTime(): string
    {
        return $this->data['datetime'];
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
     * Transaction type: 0 - deposit; 1 - withdrawal; 2 - market trade; 14 - sub account transfer.
     *
     * @return int
     */
    public function getType(): int
    {
        return (int) $this->data['type'];
    }

    /**
     * USD Amount.
     *
     * @return float
     */
    public function usd(): float
    {
        return (float) $this->data['usd'];
    }

    /**
     * EUR Amount.
     *
     * @return float
     */
    public function eur(): float
    {
        return (float) $this->data['eur'];
    }

    /**
     * BTC Amount.
     *
     * @return float
     */
    public function btc(): float
    {
        return (float) $this->data['btc'];
    }

    /**
     * XRP Amount.
     *
     * @return float
     */
    public function xrp(): float
    {
        return (float) $this->data['xrp'];
    }

    /**
     * Exchange rate.
     * btc_usd.
     *
     * @return float
     */
    public function btcusdExchangeRate(): float
    {
        return isset($this->data['btc_usd']) ? (float) $this->data['btc_usd'] : 0.0;
    }

    /**
     * Exchange rate.
     * btc_eur.
     *
     * @return float
     */
    public function btceurExchangeRate(): float
    {
        return isset($this->data['btc_eur']) ? (float) $this->data['btc_eur'] : 0.0;
    }

    /**
     * Transaction fee.
     *
     * @return float
     */
    public function getFee(): float
    {
        return (float) $this->data['fee'];
    }

    /**
     * Transaction fee.
     *
     * @return int
     */
    public function getOrderId(): int
    {
        return (int) $this->data['order_id'];
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
