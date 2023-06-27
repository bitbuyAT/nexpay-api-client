<?php

namespace bitbuyAT\Globitex\Objects;

class ExecutionReport
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
     * Get Order ID.
     */
    public function orderId(): int
    {
        return $this->data['orderId'];
    }

    /**
     * Get Client Order ID.
     *
     * @return int
     */
    public function clientOrderId(): string
    {
        return $this->data['clientOrderId'];
    }

    /**
     * Get Order Status.
     */
    public function orderStatus(): string
    {
        return $this->data['orderStatus'];
    }

    /**
     * Get Currency Symbol.
     */
    public function symbol(): string
    {
        return $this->data['symbol'];
    }

    /**
     * Get Side Of the Trade (buy or sell).
     */
    public function side(): string
    {
        return $this->data['side'];
    }

    /**
     * Get Price.
     */
    public function price(): string
    {
        return $this->data['price'];
    }

    /**
     * Get Quantity.
     *
     * Order quantity, in natural currency units (e.g. 1.00000001 for BTCEUR when representing 1 bitcoin and 1 satoshi)
     */
    public function quantity(): string
    {
        return $this->data['quantity'];
    }

    /**
     * Get type.
     *
     * Type of an order (limit, market, stopLimit, stop)
     */
    public function type(): string
    {
        return $this->data['type'];
    }

    /**
     * Get Time In Force.
     *
     * GTC - Good-Til-Canceled
     * IOC - Immediate-Or-Cancel
     * FOK - Fill-Or-Kill
     * DAY - Day
     * GTD - Good-Till-Date
     */
    public function timeInForce(): string
    {
        return $this->data['timeInForce'];
    }

    /**
     * Get Expire Time.
     *
     * UTC timestamp in milliseconds
     */
    public function expireTime(): int
    {
        return $this->data['expireTime'];
    }

    /**
     * Get Order Stop Price.
     *
     * @return string (decimal as string)
     */
    public function stopPrice(): string
    {
        return $this->data['stopPrice'];
    }

    /**
     * Get Last Quantity.
     *
     * Quantity of last executed trade in natural currency units
     * (e.g. 1.00000001 for BTCEUR when representing 1 bitcoin and 1 satoshi)
     *
     * @return string (decimal as string)
     */
    public function lastQuantity(): string
    {
        return $this->data['lastQuantity'];
    }

    /**
     * Get Last Price.
     *
     * Price of the last executed quantity
     *
     * @return string (decimal as string)
     */
    public function lastPrice(): string
    {
        return $this->data['lastPrice'];
    }

    /**
     * Get Leaves Quantity.
     *
     * Remaining quantity, in natural currency units
     * (e.g. 1.00000001 for BTCEUR when representing 1 bitcoin and 1 satoshi)
     *
     * @return string (decimal as string)
     */
    public function leavesQuantity(): string
    {
        return $this->data['leavesQuantity'];
    }

    /**
     * Get Cumulative Quantity.
     *
     * Order total executed quantity, in natural currency units
     * (e.g. 1.00000001 for BTCEUR when representing 1 bitcoin and 1 satoshi)
     *
     * @return string (decimal as string)
     */
    public function cumQuantity(): string
    {
        return $this->data['cumQuantity'];
    }

    /**
     * Get Average Price.
     *
     * @return string (decimal as string)
     */
    public function averagePrice(): string
    {
        return $this->data['averagePrice'];
    }

    /**
     * Get Creation Date.
     *
     * UTC timestamp when order request was received on server side. In milliseconds
     */
    public function created(): int
    {
        return $this->data['created'];
    }

    /**
     * Get Execution Report Type.
     *
     * new
     * rejected
     * suspended
     */
    public function execReportType(): string
    {
        return $this->data['execReportType'];
    }

    /**
     * Get Reason for Order Rejection.
     * /**
     * Get Timestamp.
     *
     * UTC timestamp of order placement. In milliseconds
     */
    public function timestamp(): string
    {
        return $this->data['timestamp'];
    }

    /**
     * Get Account.
     *
     * Exchange account number the order is placed on
     */
    public function account(): string
    {
        return $this->data['account'];
    }

    /**
     * Get Order Source.
     *
     * Channel for the order placement (e.g. REST)
     */
    public function orderSource(): string
    {
        return $this->data['orderSource'];
    }

    /**
     * Whole data array.
     */
    public function getData(): array
    {
        return $this->data;
    }
}
