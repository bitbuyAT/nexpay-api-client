<?php

namespace bitbuyAT\Nexpay\Objects;

class Pair
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
     * Instrument symbol.
     */
    public function symbol(): string
    {
        return $this->data['symbol'];
    }

    /**
     * Order price minimum increment parameter.
     */
    public function priceIncrement(): string
    {
        return $this->data['priceIncrement'];
    }

    /**
     * Order size minimum increment parameter.
     */
    public function sizeIncrement(): int
    {
        return $this->data['sizeIncrement'];
    }

    /**
     * Minimum order size.
     */
    public function sizeMin(): int
    {
        return $this->data['sizeMin'];
    }

    /**
     * Price currency for the instrument.
     */
    public function currency(): string
    {
        return $this->data['currency'];
    }

    /**
     * Base currency for the instrument.
     */
    public function commodity(): string
    {
        return $this->data['commodity'];
    }

    /**
     * Whole data array.
     */
    public function getData(): array
    {
        return $this->data;
    }
}
