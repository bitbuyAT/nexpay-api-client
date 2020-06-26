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
     * Instrument symbol.
     *
     * @return string
     */
    public function symbol(): string
    {
        return $this->data['symbol'];
    }

    /**
     * Order price minimum increment parameter.
     *
     * @return string
     */
    public function priceIncrement(): string
    {
        return $this->data['priceIncrement'];
    }

    /**
     * Order size minimum increment parameter.
     *
     * @return int
     */
    public function sizeIncrement(): int
    {
        return $this->data['sizeIncrement'];
    }

    /**
     * Minimum order size
     *
     * @return int
     */
    public function sizeMin(): int
    {
        return $this->data['sizeMin'];
    }

    /**
     * Price currency for the instrument.
     *
     * @return string
     */
    public function currency(): string
    {
        return $this->data['currency'];
    }

    /**
     * Base currency for the instrument
     *
     * @return string
     */
    public function commodity(): string
    {
        return $this->data['commodity'];
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
