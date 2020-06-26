<?php

namespace bitbuyAT\Globitex\Contracts;

use bitbuyAT\Globitex\Objects\Balance;
use bitbuyAT\Globitex\Objects\OrderBook;
use bitbuyAT\Globitex\Objects\PairsCollection;
use bitbuyAT\Globitex\Objects\Ticker;
use bitbuyAT\Globitex\Objects\TradesCollection;
use bitbuyAT\Globitex\Objects\UserTransactionsCollection;
use bitbuyAT\Globitex\Exceptions\GlobitexApiErrorException;

interface Client
{
     /**
     * Returns the server time in UNIX timestamp format. Precision – milliseconds.
     *
     * @param string $pair
     *
     * @return int
     *
     * @throws GlobitexApiErrorException
     */
    public function getTime(): int;

    /**
     * Get ticker information.
     *
     * @param string $pair
     *
     * @return Ticker
     *
     * @throws GlobitexApiErrorException
     */
    public function getTicker(string $pair): Ticker;

    /**
     * Get order book.
     *
     * @param string $pair
     * 
     * @return OrderBook
     *
     * @throws GlobitexApiErrorException
     */
    public function getOrderBook(string $pair): OrderBook;

    /**
     * Get current trades.
     *
     * @param string $pair
     * @param string $formatItem Format of items returned: as a list of object (default) or as an array
     *
     * @return TradesCollection|Trade[]
     *
     * @throws GlobitexApiErrorException
     */
    public function getTrades(string $pair, ?string $formatItem = 'object'): TradesCollection;

    /**
     * Get tradable asset pairs.
     *
     * @return PairsCollection|Pair[]
     *
     * @throws GlobitexApiErrorException
     */
    public function getAssetPairs(): PairsCollection;

    /**
     * Get account balance.
     *
     * @return Balance
     *
     * @throws GlobitexApiErrorException
     */
    public function getAccountBalance(): Balance;

    /**
     * Get user trades.
     *
     * @param string [$pair=null] - Pair to filter for, if left empty there will be queried for all pairs (default: null)
     * @param int [$offset=0] - Skip that many trades before returning results (default: 0)
     * @param int [$limit=100] - Limit result to that many trades (default: 100; maximum: 1000)
     * @param string [$sort='desc'] - Sorting by date and time: asc - ascending; desc - descending (default: desc)
     * @param int [$sinceTimestamp] - Show only trades from unix timestamp (for max 30 days old)
     *
     * @return UserTransactionsCollection|Trade[]
     *
     * @throws GlobitexApiErrorException
     */
    public function getUserTransactions(?string $pair = null, ?int $offset = 0, ?int $limit = 100, ?string $sort = 'desc', ?int $sinceTimestamp = null): UserTransactionsCollection;

    /**
     * Make public request request
     * Currently only get request.
     *
     * @param string $method
     * @param string $path
     * @param array  $parameters
     *
     * @return array
     *
     * @throws GlobitexApiErrorException
     */
    public function publicRequest(string $method, string $path = '', $parameters = []): array;

    /**
     * Make private request request
     * Currently only post request.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return array
     *
     * @throws GlobitexApiErrorException
     */
    public function privateRequest(string $method, array $parameters = []): array;
}
