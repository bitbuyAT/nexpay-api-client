<?php

namespace bitbuyAT\Globitex\Objects;

class CryptoTransactionFee
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
     * Get recommended withdrawal fee (should ensure quick inclusion in the respective cryptocurrency ledger)
     *
     * @return string
     */
    public function recommended(): string
    {
        return $this->data['recommended'];
    }

    /**
     * Get minimum withdrawal fee that can be specified for withdrawal operation (this can lead to transaction hanging in the block-chain for a long time)
     *
     * @return string
     */
    public function minimum(): string
    {
        return $this->data['minimum'];
    }

    /**
     * Maximum withdrawal fee that can be specified (while specification of fee amount which is larger than recommended is possible it's in most cases not required)
     *
     * @return string
     */
    public function maximum(): string
    {
        return $this->data['maximum'];
    }

    /**
     * Provided fee interval identifier, can be provided when creating cryptocurrency transfer to ensure commission will be within previously provided limits.
     *
     * @return int
     */
    public function feeId(): int
    {
        return $this->data['feeId'];
    }

    /**
     * Fee expire time (UNIX timestamp in milliseconds since epoch).
     *
     * @return int
     */
    public function feeExpireTime(): int
    {
        return $this->data['feeExpireTime'];
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
