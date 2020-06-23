<?php

namespace bitbuyAT\Globitex\Objects;

class Pair
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
     * Pair name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->data['name'];
    }

    /**
     * URL symbol of trading pair.
     *
     * @return string
     */
    public function urlSymbol(): string
    {
        return $this->data['url_symbol'];
    }

    /**
     * Decimal precision for base currency (f.e. BTC/USD - base: BTC).
     *
     * @return int
     */
    public function baseDecimals(): int
    {
        return $this->data['base_decimals'];
    }

    /**
     * Decimal precision for counter currency (f.e. BTC/USD - counter: USD).
     *
     * @return int
     */
    public function counterDecimals(): int
    {
        return $this->data['counter_decimals'];
    }

    /**
     * Minimum order size. (includes asset code in string).
     *
     * @return string
     */
    public function minimumOrder(): string
    {
        return $this->data['minimum_order'];
    }

    /**
     * Trading engine status (Enabled/Disabled).
     *
     * @return string
     */
    public function tradingStatus(): string
    {
        return $this->data['trading'];
    }

    /**
     * Trading pair description.
     *
     * @return string
     */
    public function description(): string
    {
        return $this->data['description'];
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
