<?php

namespace bitbuyAT\Nexpay\Objects;

class CryptoTransactionFee
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
     * Get recommended withdrawal fee (should ensure quick inclusion in the respective cryptocurrency ledger).
     */
    public function recommended(): string
    {
        return $this->data['recommended'];
    }

    /**
     * Get minimum withdrawal fee that can be specified for withdrawal operation (this can lead to transaction hanging in the block-chain for a long time).
     */
    public function minimum(): string
    {
        return $this->data['minimum'];
    }

    /**
     * Maximum withdrawal fee that can be specified (while specification of fee amount which is larger than recommended is possible it's in most cases not required).
     */
    public function maximum(): string
    {
        return $this->data['maximum'];
    }

    /**
     * Provided fee interval identifier, can be provided when creating cryptocurrency transfer to ensure commission will be within previously provided limits.
     */
    public function feeId(): int
    {
        return $this->data['feeId'];
    }

    /**
     * Fee expire time (UNIX timestamp in milliseconds since epoch).
     */
    public function feeExpireTime(): int
    {
        return $this->data['feeExpireTime'];
    }

    /**
     * Whole data array.
     */
    public function getData(): array
    {
        return $this->data;
    }
}
