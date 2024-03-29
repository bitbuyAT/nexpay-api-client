<?php

namespace bitbuyAT\Nexpay\Objects;

class NewOrderParameters
{
    /**
     * @var array
     */
    protected $parameters;

    /**
     * @param array $parameters
     *
     * Array keys:
     * 'clientOrderId'  Unique order ID generated by client. Can not match active order on the same account.
     *                  From 8 to 32 characters. If not provided, will be generated automatically
     * 'account' Account number
     * 'symbol' Instrument symbol traded on exchange (see Instrument symbols), e.g. BTCEUR
     * 'side' Side of a trade - buy or sell
     * 'price' Order price - decimal value as string
     * 'quantity'   Order quantity in natural currency units
     *              (e.g. 1.00000001 for BTCEUR when representing 1 bitcoin and 1 satoshi) - decimal value as string
     * 'type' Order type. Default value - limit (limit or market, stop, stopLimit)
     * 'timeInForce' Time in force. Default value – GTC (GTC, GTD, IOC, FOK, DAY)
     * 'expireTime' UTC timestamp in seconds - 64 bit integer
     * 'stopPrice' Order stop price - decimal value as string
     */
    public function __construct(array $parameters)
    {
        $this->setClientOrderId($parameters['clientOrderId']);
        $this->setAccount($parameters['account']);
        $this->setSymbol($parameters['symbol']);
        $this->setSide($parameters['side']);
        if (!array_key_exists('type', $parameters)
            || $parameters['type'] === 'limit'
            || $parameters['type'] === 'stopLimit'
        ) {
            $this->setPrice($parameters['price']);
        }
        $this->setQuantity($parameters['quantity']);
        array_key_exists('type', $parameters) ? $this->setType($parameters['type']) : 0;
        array_key_exists('timeInForce', $parameters) ? $this->setTimeInForce($parameters['timeInForce']) : 0;
        if (array_key_exists('timeInForce', $parameters) && $parameters['timeInForce'] === 'GTD') {
            $this->setExpireTime($parameters['expireTime']);
        }
        if (array_key_exists('type', $parameters)
            && ($parameters['type'] === 'stop' || $parameters['type'] === 'stopLimit')
        ) {
            $this->setStopPrice($parameters['stopPrice']);
        }
    }

    /**
     * Set Client Order ID.
     */
    public function setClientOrderId($clientOrderId)
    {
        $this->parameters['clientOrderId'] = $clientOrderId;
    }

    /**
     * Get Client Order ID.
     */
    public function getClientOrderId(): string
    {
        return $this->parameters['clientOrderId'];
    }

    /**
     * Set Account.
     */
    public function setAccount($account)
    {
        $this->parameters['account'] = $account;
    }

    /**
     * Get Account.
     */
    public function getAccount(): string
    {
        return $this->parameters['account'];
    }

    /**
     * Set Symbol.
     */
    public function setSymbol($symbol)
    {
        $this->parameters['symbol'] = $symbol;
    }

    /**
     * Get symbol.
     */
    public function getSymbol(): string
    {
        return $this->parameters['symbol'];
    }

    /**
     * Set Side.
     */
    public function setSide($side)
    {
        $this->parameters['side'] = $side;
    }

    /**
     * Get side.
     */
    public function getSide(): string
    {
        return $this->parameters['side'];
    }

    /**
     * Set price.
     */
    public function setPrice($price)
    {
        $this->parameters['price'] = $price;
    }

    /**
     * Get price.
     */
    public function getPrice(): string
    {
        return $this->parameters['price'];
    }

    /**
     * Set quantity.
     */
    public function setQuantity($quantity)
    {
        $this->parameters['quantity'] = $quantity;
    }

    /**
     * Get quantity.
     */
    public function getQuantity(): string
    {
        return $this->parameters['quantity'];
    }

    /**
     * Set type.
     */
    public function setType($type)
    {
        $this->parameters['type'] = $type;
    }

    /**
     * Get type.
     */
    public function getType(): string
    {
        return $this->parameters['type'];
    }

    /**
     * Set timeInForce.
     */
    public function setTimeInForce($timeInForce)
    {
        $this->parameters['timeInForce'] = $timeInForce;
    }

    /**
     * Get timeInForce.
     */
    public function getTimeInForce(): string
    {
        return $this->parameters['timeInForce'];
    }

    /**
     * Set expireTime.
     */
    public function setExpireTime($expireTime)
    {
        $this->parameters['expireTime'] = $expireTime;
    }

    /**
     * Get expireTime.
     */
    public function getExpireTime(): int
    {
        return $this->parameters['expireTime'];
    }

    /**
     * Set stopPrice.
     */
    public function setStopPrice($stopPrice)
    {
        $this->parameters['stopPrice'] = $stopPrice;
    }

    /**
     * Get stopPrice.
     */
    public function getStopPrice(): string
    {
        return $this->parameters['stopPrice'];
    }

    /**
     * Get whole array.
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
