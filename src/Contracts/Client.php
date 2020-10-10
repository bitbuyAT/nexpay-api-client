<?php

namespace bitbuyAT\Globitex\Contracts;

use bitbuyAT\Globitex\Exceptions\GlobitexApiErrorException;
use bitbuyAT\Globitex\Objects\Account;
use bitbuyAT\Globitex\Objects\AccountsCollection;
use bitbuyAT\Globitex\Objects\CryptoTransactionFee;
use bitbuyAT\Globitex\Objects\EuroAccountsCollection;
use bitbuyAT\Globitex\Objects\EuroPaymentHistory;
use bitbuyAT\Globitex\Objects\ExecutionReport;
use bitbuyAT\Globitex\Objects\GBXUtilizationTransactionsCollection;
use bitbuyAT\Globitex\Objects\NewOrderParameters;
use bitbuyAT\Globitex\Objects\OrderBook;
use bitbuyAT\Globitex\Objects\PairsCollection;
use bitbuyAT\Globitex\Objects\Ticker;
use bitbuyAT\Globitex\Objects\TradesCollection;
use bitbuyAT\Globitex\Objects\TransactionsCollection;

interface Client
{
    /**
     * Returns the server time in UNIX timestamp format. Precision – milliseconds.
     *
     * @throws GlobitexApiErrorException
     */
    public function getTime(): int;

    /**
     * Get ticker information.
     *
     * @throws GlobitexApiErrorException
     */
    public function getTicker(string $pair): Ticker;

    /**
     * Get order book.
     *
     * @throws GlobitexApiErrorException
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
     * Place New Order.
     * Returns a JSON object ExecutionReport that represent a status of the order.
     *
     * @param NewOrderParameters
     *
     * @throws GlobitexApiErrorException
     */
    public function placeNewOrder(NewOrderParameters $newOrderParams): ExecutionReport;

    /**
     * Get account balance.
     *
     * @return AccountsCollection|Account[]
     *
     * @throws GlobitexApiErrorException
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
     * @throws GlobitexApiErrorException
     */
    public function getCryptoTransactionFee(string $currency, string $amount, string $account): CryptoTransactionFee;

    /**
     * Get Cryptocurrency Deposit Address.
     * Returns the previously created incoming cryptocurrency address that can be used to deposit cryptocurrency to your account.
     *
     * @param string $currency Currency code e.g. BTC, for the cryptocurrency address
     * @param string $amount   Account number the funds will be deposited on. If not provided the cryptocurrency deposit address for the default account will be provided (sample value: XAZ123A91)
     *
     * @return string $address Cryptocurrency deposit address
     *
     * @throws GlobitexApiErrorException
     */
    public function getCryptoCurrencyDepositAddress(string $currency, ?string $account = null): string;

    /**
     * Get transactions.
     * Returns a list of payment transactions and their status (array of transactions).
     *
     * @param array $params=[] Optional Parameters
     *                         Params can be found under https://globitex.com/api/#GetTransactionList
     *
     * @return TransactionsCollection|Transaction[]
     *
     * @throws GlobitexApiErrorException
     */
    public function getTransactions(array $params = []): TransactionsCollection;

    /**
     * Get GBX (Globitex Token) Utilization List.
     * Returns a list of GBX utilization transactions (array of transactions).
     *
     * @param array $params=[] - Optional Parameters
     *                         Params can be found under https://globitex.com/api/#GbxUtilizationList
     *
     * @return GBXUtilizationTransactionsCollection|GBXUtilizationTransaction[]
     *
     * @throws GlobitexApiErrorException
     */
    public function getGBXUtilizationTransactions(array $params = []): GBXUtilizationTransactionsCollection;

    /**
     * Returns default (single) or all account status information.
     *
     * @return EuroAccountsCollection|EuroAccount[]
     *
     * @throws GlobitexApiErrorException
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
     * @throws GlobitexApiErrorException
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
     * @throws GlobitexApiErrorException
     */
    public function publicRequest(string $method, string $path = '', $parameters = []): array;

    /**
     * Make private request request.
     *
     * @param string $method            api method
     * @param array  $parameters        query parameters
     * @param string $httpMethod='post' http method
     *
     * @throws GlobitexApiErrorException
     */
    public function privateRequest(string $method, array $parameters = []): array;
}
