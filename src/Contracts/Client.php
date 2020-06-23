<?php

namespace bitbuyAT\Globitex\Contracts;

use bitbuyAT\Globitex\Objects\Balance;
use bitbuyAT\Globitex\Objects\OrderBook;
use bitbuyAT\Globitex\Objects\PairsCollection;
use bitbuyAT\Globitex\Objects\Ticker;
use bitbuyAT\Globitex\Objects\TransactionsCollection;
use bitbuyAT\Globitex\Objects\UserTransactionsCollection;
use bitbuyAT\Globitex\Exceptions\GlobitexApiErrorException;

interface Client
{
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
     * Get hourly ticker information.
     *
     * @param string $pair
     *
     * @return Ticker
     *
     * @throws GlobitexApiErrorException
     */
    public function getHourlyTicker(string $pair): Ticker;

    /**
     * Get order book.
     *
     * @param string $pair
     * @param int    $group optional group
     *                      0: orders are not grouped at same price
     *                      1: orders are grouped at same price - default
     *                      2: orders with their order ids are not grouped at same price
     *
     * @return OrderBook
     *
     * @throws GlobitexApiErrorException
     */
    public function getOrderBook(string $pair, ?int $group = 1): OrderBook;

    /**
     * Get current transactions.
     *
     * @param string $pair
     * @param string $time The time interval from which we want the transactions to be returned. Possible values are minute, hour (default) or day.
     *
     * @return TransactionsCollection|Transaction[]
     *
     * @throws GlobitexApiErrorException
     */
    public function getTransactions(string $pair, ?string $time = 'hour'): TransactionsCollection;

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
     * Get user transactions.
     *
     * @param string [$pair=null] - Pair to filter for, if left empty there will be queried for all pairs (default: null)
     * @param int [$offset=0] - Skip that many transactions before returning results (default: 0)
     * @param int [$limit=100] - Limit result to that many transactions (default: 100; maximum: 1000)
     * @param string [$sort='desc'] - Sorting by date and time: asc - ascending; desc - descending (default: desc)
     * @param int [$sinceTimestamp] - Show only transactions from unix timestamp (for max 30 days old)
     *
     * @return UserTransactionsCollection|Transaction[]
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
