<?php

namespace bitbuyAT\Nexpay\Contracts;

use bitbuyAT\Nexpay\Exceptions\NexpayApiErrorException;
use bitbuyAT\Nexpay\Objects\Account;
use bitbuyAT\Nexpay\Objects\AccountsCollection;
use bitbuyAT\Nexpay\Objects\CryptoTransactionFee;
use bitbuyAT\Nexpay\Objects\EuroAccountsCollection;
use bitbuyAT\Nexpay\Objects\EuroPaymentHistory;
use bitbuyAT\Nexpay\Objects\ExecutionReport;
use bitbuyAT\Nexpay\Objects\ExecutionReportsCollection;
use bitbuyAT\Nexpay\Objects\GBXUtilizationTransactionsCollection;
use bitbuyAT\Nexpay\Objects\GetMyTradesParameters;
use bitbuyAT\Nexpay\Objects\MyTradesCollection;
use bitbuyAT\Nexpay\Objects\NewOrderParameters;
use bitbuyAT\Nexpay\Objects\OrderBook;
use bitbuyAT\Nexpay\Objects\PairsCollection;
use bitbuyAT\Nexpay\Objects\Ticker;
use bitbuyAT\Nexpay\Objects\TradesCollection;
use bitbuyAT\Nexpay\Objects\TransactionsCollection;

interface Client
{
    /**
     * Returns the server time in UNIX timestamp format. Precision – milliseconds.
     *
     * @throws NexpayApiErrorException
     */
    public function getTime(): int;

    /**
     * Get ticker information.
     *
     * @throws NexpayApiErrorException
     */
    public function getTicker(string $pair): Ticker;

    /**
     * Get order book.
     *
     * @throws NexpayApiErrorException
     */
    public function getOrderBook(string $pair): OrderBook;

    /**
     * Get current trades.
     *
     * @param string $pair       Pair to get trades of
     * @param string $formatItem Format of items returned: as a list of object (default) or as an array
     *
     * @return TradesCollection|Trade[]
     *
     * @throws NexpayApiErrorException
     */
    public function getTrades(string $pair, ?string $formatItem = 'object'): TradesCollection;

    /**
     * Get tradable asset pairs.
     *
     * @return PairsCollection|Pair[]
     *
     * @throws NexpayApiErrorException
     */
    public function getAssetPairs(): PairsCollection;

    /**
     * Place New Order.
     * Returns a JSON object ExecutionReport that represent a status of the order.
     *
     * @param NewOrderParameters
     *
     * @throws NexpayApiErrorException
     */
    public function placeNewOrder(NewOrderParameters $newOrderParams): ExecutionReport;

    /**
     * Cancel an Order.
     * Returns an ExecutionReport that represent a status of the order.
     *
     * @param NewOrderParameters
     *
     * @throws NexpayApiErrorException
     */
    public function cancelOrder(string $clientOrderId, string $account): ExecutionReport;

    /**
     * Cancel all Orders.
     * Returns an ExecutionReport that represent a status of the order.
     *
     * @param NewOrderParameters
     *
     * @throws NexpayApiErrorException
     */
    public function cancelAllOrders(array $params = []): ExecutionReportsCollection;

    /**
     * Get My Trades
     * Returns the trading history - a collection of client's trades (MyTrade objects).
     *
     * @param GetMyTradesParameters
     *
     * @throws NexpayApiErrorException
     */
    public function getMyTrades(GetMyTradesParameters $getMyTradesParams): MyTradesCollection;

    /**
     * Get account balance.
     *
     * @return AccountsCollection|Account[]
     *
     * @throws NexpayApiErrorException
     */
    public function getAccountBalance(): AccountsCollection;

    /**
     * Get Crypto Transaction Fee.
     * Returns cryptocurrency withdrawal (miner) fee based on the provided parameters.
     *
     * @param string $currency Currency code e.g. BTC
     * @param string $amount   Withdrawal amount decimal (for example 1.23)
     * @param string $account  number from which funds will be withdrawn (for example: XAZ123A91)
     *
     * @throws NexpayApiErrorException
     */
    public function getCryptoTransactionFee(string $currency, string $amount, string $account): CryptoTransactionFee;

    /**
     * Get Cryptocurrency Deposit Address.
     * Returns the previously created incoming cryptocurrency address that can be used to deposit cryptocurrency to your account.
     *
     * @param string $currency Currency code e.g. BTC, for the cryptocurrency address
     *
     * @return string $address Cryptocurrency deposit address
     *
     * @throws NexpayApiErrorException
     */
    public function getCryptoCurrencyDepositAddress(string $currency, string $account = null): string;

    /**
     * Get transactions.
     * Returns a list of payment transactions and their status (array of transactions).
     *
     * @return TransactionsCollection|Transaction[]
     *
     * @throws NexpayApiErrorException
     */
    public function getTransactions(array $params = []): TransactionsCollection;

    /**
     * Get GBX (Nexpay Token) Utilization List.
     * Returns a list of GBX utilization transactions (array of transactions).
     *
     * @return GBXUtilizationTransactionsCollection|GBXUtilizationTransaction[]
     *
     * @throws NexpayApiErrorException
     */
    public function getGBXUtilizationTransactions(array $params = []): GBXUtilizationTransactionsCollection;

    /**
     * Returns default (single) or all account status information.
     *
     * @return EuroAccountsCollection|EuroAccount[]
     *
     * @throws NexpayApiErrorException
     */
    public function getEuroAccountStatus(): EuroAccountsCollection;

    /**
     * Returns default (single) or all account status information.
     *
     * @param string $fromDate Date from to display account history. String in ISO 8601 format of yyyy-MM-dd, e.g. "2000-10-31"
     * @param string $toDate   End date of account history to use in search criteria. String in ISO 8601 format of yyyy-MM-dd, e.g. "2000-10-31"
     * @param string $account  Account IBAN number to use in search criteria. If not provided then default account number will be used
     *
     * @return EuroAccountsCollection|EuroAccount[]
     *
     * @throws NexpayApiErrorException
     */
    public function getEuroPaymentHistory(string $fromDate = null, string $toDate = null, string $account = null): EuroPaymentHistory;

    /**
     * Make public request request
     * Currently only get request.
     *
     * @param string $method     api method
     * @param string $path       additional path
     * @param array  $parameters query parameters
     *
     * @throws NexpayApiErrorException
     */
    public function publicRequest(string $method, string $path = '', $parameters = []): array;

    /**
     * Make private request request.
     *
     * @param string $method     api method
     * @param array  $parameters query parameters
     *
     * @throws NexpayApiErrorException
     */
    public function privateRequest(string $method, array $parameters = []): array;
}
