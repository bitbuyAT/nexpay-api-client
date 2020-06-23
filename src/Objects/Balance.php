<?php

namespace bitbuyAT\Globitex\Objects;

class Balance
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
     * Balance BCH.
     *
     * @return float
     */
    public function bchBalance(): float
    {
        return (float) $this->data['bch_balance'];
    }

    /**
     * Reserved BCH.
     *
     * @return float
     */
    public function bchReserved(): float
    {
        return (float) $this->data['bch_reserved'];
    }

    /**
     * Available BCH.
     *
     * @return float
     */
    public function bchAvailable(): float
    {
        return (float) $this->data['bch_available'];
    }

    /**
     * Balance BTC.
     *
     * @return float
     */
    public function btcBalance(): float
    {
        return (float) $this->data['btc_balance'];
    }

    /**
     * Reserved BTC.
     *
     * @return float
     */
    public function btcReserved(): float
    {
        return (float) $this->data['btc_reserved'];
    }

    /**
     * Available BTC.
     *
     * @return float
     */
    public function btcAvailable(): float
    {
        return (float) $this->data['btc_available'];
    }

    /**
     * Balance ETH.
     *
     * @return float
     */
    public function ethBalance(): float
    {
        return (float) $this->data['eth_balance'];
    }

    /**
     * Reserved ETH.
     *
     * @return float
     */
    public function ethReserved(): float
    {
        return (float) $this->data['eth_reserved'];
    }

    /**
     * Available ETH.
     *
     * @return float
     */
    public function ethAvailable(): float
    {
        return (float) $this->data['eth_available'];
    }

    /**
     * Balance EUR.
     *
     * @return float
     */
    public function eurBalance(): float
    {
        return (float) $this->data['eur_balance'];
    }

    /**
     * Reserved EUR.
     *
     * @return float
     */
    public function eurReserved(): float
    {
        return (float) $this->data['eur_reserved'];
    }

    /**
     * Available EUR.
     *
     * @return float
     */
    public function eurAvailable(): float
    {
        return (float) $this->data['eur_available'];
    }

    /**
     * Balance LTC.
     *
     * @return float
     */
    public function ltcBalance(): float
    {
        return (float) $this->data['ltc_balance'];
    }

    /**
     * Reserved LTC.
     *
     * @return float
     */
    public function ltcReserved(): float
    {
        return (float) $this->data['ltc_reserved'];
    }

    /**
     * Available LTC.
     *
     * @return float
     */
    public function ltcAvailable(): float
    {
        return (float) $this->data['ltc_available'];
    }

    /**
     * Balance USD.
     *
     * @return float
     */
    public function usdBalance(): float
    {
        return (float) $this->data['usd_balance'];
    }

    /**
     * Reserved USD.
     *
     * @return float
     */
    public function usdReserved(): float
    {
        return (float) $this->data['usd_reserved'];
    }

    /**
     * Available USD.
     *
     * @return float
     */
    public function usdAvailable(): float
    {
        return (float) $this->data['usd_available'];
    }

    /**
     * Balance XRP.
     *
     * @return float
     */
    public function xrpBalance(): float
    {
        return (float) $this->data['xrp_balance'];
    }

    /**
     * Reserved XRP.
     *
     * @return float
     */
    public function xrpReserved(): float
    {
        return (float) $this->data['xrp_reserved'];
    }

    /**
     * Available XRP.
     *
     * @return float
     */
    public function xrpAvailable(): float
    {
        return (float) $this->data['xrp_available'];
    }

    /**
     * Customer BCH/BTC trading fee.
     *
     * @return float
     */
    public function bchbtcFee(): float
    {
        return (float) $this->data['bchbtc_fee'];
    }

    /**
     * Customer BCH/EUR trading fee.
     *
     * @return float
     */
    public function bcheurFee(): float
    {
        return (float) $this->data['bcheur_fee'];
    }

    /**
     * Customer BCH/USD trading fee.
     *
     * @return float
     */
    public function bchusdFee(): float
    {
        return (float) $this->data['bchusd_fee'];
    }

    /**
     * Customer BTC/USD trading fee.
     *
     * @return float
     */
    public function btcusdFee(): float
    {
        return (float) $this->data['btcusd_fee'];
    }

    /**
     * Customer BTC/EUR trading fee.
     *
     * @return float
     */
    public function btceurFee(): float
    {
        return (float) $this->data['btceur_fee'];
    }

    /**
     * Customer ETH/BTC trading fee.
     *
     * @return float
     */
    public function ethbtcFee(): float
    {
        return (float) $this->data['ethbtc_fee'];
    }

    /**
     * Customer ETH/EUR trading fee.
     *
     * @return float
     */
    public function etheurFee(): float
    {
        return (float) $this->data['etheur_fee'];
    }

    /**
     * Customer ETH/USD trading fee.
     *
     * @return float
     */
    public function ethusdFee(): float
    {
        return (float) $this->data['ethusd_fee'];
    }

    /**
     * Customer EUR/USD trading fee.
     *
     * @return float
     */
    public function eurusdFee(): float
    {
        return (float) $this->data['eurusd_fee'];
    }

    /**
     * Customer LTC/BTC trading fee.
     *
     * @return float
     */
    public function ltcbtcFee(): float
    {
        return (float) $this->data['ltcbtc_fee'];
    }

    /**
     * Customer LTC/EUR trading fee.
     *
     * @return float
     */
    public function ltceurFee(): float
    {
        return (float) $this->data['ltceur_fee'];
    }

    /**
     * Customer LTC/USD trading fee.
     *
     * @return float
     */
    public function ltcusdFee(): float
    {
        return (float) $this->data['ltcusd_fee'];
    }

    /**
     * Customer XRP/BTC trading fee.
     *
     * @return float
     */
    public function xrpbtcFee(): float
    {
        return (float) $this->data['xrpbtc_fee'];
    }

    /**
     * Customer XRP/EUR trading fee.
     *
     * @return float
     */
    public function xrpeurdFee(): float
    {
        return (float) $this->data['xrpeur_fee'];
    }

    /**
     * Customer XRP/USD trading fee.
     *
     * @return float
     */
    public function xrpusdFee(): float
    {
        return (float) $this->data['xrpusd_fee'];
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
