<?php

namespace bitbuyAT\Globitex;

use bitbuyAT\Globitex\Contracts\Client as ClientContract;
use bitbuyAT\Globitex\Exceptions\GlobitexApiErrorException;
use bitbuyAT\Globitex\Objects\Account;
use bitbuyAT\Globitex\Objects\AccountsCollection;
use bitbuyAT\Globitex\Objects\CryptoTransactionFee;
use bitbuyAT\Globitex\Objects\EuroAccount;
use bitbuyAT\Globitex\Objects\EuroAccountsCollection;
use bitbuyAT\Globitex\Objects\EuroPaymentHistory;
use bitbuyAT\Globitex\Objects\ExecutionReport;
use bitbuyAT\Globitex\Objects\ExecutionReportsCollection;
use bitbuyAT\Globitex\Objects\GBXUtilizationTransaction;
use bitbuyAT\Globitex\Objects\GBXUtilizationTransactionsCollection;
use bitbuyAT\Globitex\Objects\GetMyTradesParameters;
use bitbuyAT\Globitex\Objects\MyTrade;
use bitbuyAT\Globitex\Objects\MyTradesCollection;
use bitbuyAT\Globitex\Objects\NewOrderParameters;
use bitbuyAT\Globitex\Objects\OrderBook;
use bitbuyAT\Globitex\Objects\Pair;
use bitbuyAT\Globitex\Objects\PairsCollection;
use bitbuyAT\Globitex\Objects\Ticker;
use bitbuyAT\Globitex\Objects\Trade;
use bitbuyAT\Globitex\Objects\TradesCollection;
use bitbuyAT\Globitex\Objects\Transaction;
use bitbuyAT\Globitex\Objects\TransactionsCollection;
use GuzzleHttp\ClientInterface as HttpClient;

class Client implements ClientContract
{
    const API_URL = 'https://api.globitex.com';

    /**
     * API key.
     *
     * @var string
     */
    protected $key;

    /**
     * API message signing secret.
     *
     * @var string
     */
    protected $message_secret;
    /**
     * API outgoing secret secret.
     *
     * @var string
     */
    protected $outgoing_secret;

    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @param string $key             API key
     * @param string $message_secret  API message secret
     * @param string $outgoing_secret API outgoing secret
     */
    public function __construct(
        HttpClient $client,
        ?string $key = '',
        ?string $message_secret = '',
        ?string $outgoing_secret = ''
    ) {
        $this->client = $client;
        $this->key = $key;
        $this->message_secret = $message_secret;
        $this->outgoing_secret = $outgoing_secret;
    }

    /**
     * Returns the server time in UNIX timestamp format. Precision – milliseconds.
     *
     * @throws GlobitexApiErrorException
     */
    public function getTime(): int
    {
        $result = $this->publicRequest('time');

        return $result['timestamp'];
    }

    /**
     * Get ticker information.
     *
     * @throws GlobitexApiErrorException
     */
    public function getTicker(string $pair): Ticker
    {
        $result = $this->publicRequest('ticker', $pair);

        return new Ticker($result);
    }

    /**
     * Get order book.
     *
     * @throws GlobitexApiErrorException
     */
    public function getOrderBook(string $pair): OrderBook
    {
        $result = $this->publicRequest('orderbook', $pair);

        return new OrderBook($result);
    }

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
    public function getTrades(string $pair, ?string $formatItem = 'object'): TradesCollection
    {
        $result = $this->publicRequest('trades', $pair, ['formatItem' => $formatItem]);

        return (new TradesCollection($result['trades']))->map(function ($data) {
            return new Trade($data);
        });
    }

    /**
     * Get tradable asset pairs.
     *
     * @return PairsCollection|Pair[]
     *
     * @throws GlobitexApiErrorException
     */
    public function getAssetPairs(): PairsCollection
    {
        $result = $this->publicRequest('symbols');

        return (new PairsCollection($result['symbols']))->map(function ($data) {
            return new Pair($data);
        });
    }

    /**
     * Place New Order.
     * Returns an ExecutionReport that represent a status of the order.
     *
     * @param NewOrderParameters
     *
     * @throws GlobitexApiErrorException
     */
    public function placeNewOrder(NewOrderParameters $newOrderParams): ExecutionReport
    {
        $result = $this->privateRequest('trading/new_order', $newOrderParams->getParameters(), 'post');

        return new ExecutionReport($result['ExecutionReport']);
    }

    /**
     * Cancel an Order.
     * Returns an ExecutionReport that represent a status of the order.
     *
     * @param NewOrderParameters
     *
     * @throws GlobitexApiErrorException
     */
    public function cancelOrder(string $clientOrderId, string $account): ExecutionReport
    {
        $result = $this->privateRequest('trading/cancel_order', [
            'clientOrderId' => $clientOrderId,
            'account' => $account,
            ], 'post', 2);

        return new ExecutionReport($result['ExecutionReport']);
    }

    /**
     * Cancel all Orders.
     * Returns an ExecutionReport that represent a status of the order.
     *
     * @param NewOrderParameters
     *
     * @throws GlobitexApiErrorException
     */
    public function cancelAllOrders(array $params = []): ExecutionReportsCollection
    {
        $result = $this->privateRequest('trading/cancel_orders', $params, 'post');

        return (new ExecutionReportsCollection($result['ExecutionReport']))->map(function ($data) {
            return new ExecutionReport($data);
        });
    }

    /**
     * Get My Trades
     * Returns the trading history - a collection of client's trades (MyTrade objects).
     *
     * @param GetMyTradesParameters
     *
     * @throws GlobitexApiErrorException
     */
    public function getMyTrades(GetMyTradesParameters $getMyTradesParams): MyTradesCollection
    {
        $result = $this->privateRequest('trading/trades', $getMyTradesParams->getParameters(), 'get');

        return (new MyTradesCollection($result['trades']))->map(function ($data) {
            return new MyTrade($data);
        });
    }

    /**
     * Get account balance.
     *
     * @return AccountsCollection|Account[]
     *
     * @throws GlobitexApiErrorException
     */
    public function getAccountBalance(): AccountsCollection
    {
        $result = $this->privateRequest('payment/accounts', [], 'get');

        return (new AccountsCollection($result['accounts']))->map(function ($data) {
            return new Account($data);
        });
    }

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
    public function getCryptoTransactionFee(string $currency, string $amount, string $account): CryptoTransactionFee
    {
        $result = $this->privateRequest('payment/payout/fee/crypto', [
            'currency' => $currency,
            'amount' => $amount,
            'account' => $account,
        ], 'get');

        return new CryptoTransactionFee($result);
    }

    /**
     * Get Cryptocurrency Deposit Address.
     * Returns the previously created incoming cryptocurrency address
     * that can be used to deposit cryptocurrency to your account.
     *
     * @param string $currency Currency code e.g. BTC, for the cryptocurrency address
     * @param string $account  Account number the funds will be deposited on.
     *                         If not provided the cryptocurrency deposit address for the
     *                         default account will be provided (sample value: XAZ123A91)
     *
     * @return string $address Cryptocurrency deposit address
     *
     * @throws GlobitexApiErrorException
     */
    public function getCryptoCurrencyDepositAddress(string $currency, ?string $account = null): string
    {
        $result = $this->privateRequest('payment/deposit/crypto/address', [
            'currency' => $currency,
            'account' => $account,
        ], 'get');

        return $result['address'];
    }

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
    public function getTransactions(array $params = []): TransactionsCollection
    {
        $result = $this->privateRequest('payment/transactions', $params, 'get');

        return (new TransactionsCollection($result['transactions']))->map(function ($data) {
            return new Transaction($data);
        });
    }

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
    public function getGBXUtilizationTransactions(array $params = []): GBXUtilizationTransactionsCollection
    {
        $result = $this->privateRequest('gbx-utilization/list', $params, 'get');

        return (new GBXUtilizationTransactionsCollection($result['gbxUtilizationList']))->map(function ($data) {
            return new GBXUtilizationTransaction($data);
        });
    }

    /**
     * Returns default (single) or all account status information.
     *
     * @return EuroAccountsCollection|EuroAccount[]
     *
     * @throws GlobitexApiErrorException
     */
    public function getEuroAccountStatus(): EuroAccountsCollection
    {
        $result = $this->privateRequest('eurowallet/status', [], 'get');

        return (new EuroAccountsCollection($result['accounts']))->map(function ($data) {
            return new EuroAccount($data);
        });
    }

    /**
     * Returns default (single) or all account status information.
     *
     * @param string $fromDate Date from to display account history.
     *                         String in ISO 8601 format of yyyy-MM-dd, e.g. "2000-10-31"
     * @param string $toDate   End date of account history to use in search criteria.
     *                         String in ISO 8601 format of yyyy-MM-dd, e.g. "2000-10-31"
     * @param string $account  Account IBAN number to use in search criteria.
     *                         If not provided then default account number will be used
     *
     * @return EuroAccountsCollection|EuroAccount[]
     *
     * @throws GlobitexApiErrorException
     */
    public function getEuroPaymentHistory(string $fromDate = null, string $toDate = null, string $account = null): EuroPaymentHistory
    {
        $result = $this->privateRequest('eurowallet/payments/history', [
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'account' => $account,
        ], 'get');

        return new EuroPaymentHistory($result);
    }

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
    public function publicRequest(string $method, string $path = '', $parameters = []): array
    {
        $headers = ['User-Agent' => 'Globitex PHP API Agent'];

        try {
            $response = $this->client->get($this->buildUrl($method, true).($path ? '/' : '').$path, [
                'headers' => $headers,
                'query' => $parameters,
            ]);
        } catch (\Exception $exception) {
            if ($exception->getCode() === 404) {
                throw new GlobitexApiErrorException('Endpoint not found: ('.$this->buildUrl($method).'/'.$path.')');
            } else {
                throw new GlobitexApiErrorException($exception->getMessage());
            }
        }

        return $this->decodeResult(
            $response->getBody()->getContents()
        );
    }

    /**
     * Make private request request.
     *
     * @param string $method            api method
     * @param array  $parameters        query parameters
     * @param string $httpMethod='post' http method
     *
     * @throws GlobitexApiErrorException
     */
    public function privateRequest(
        string $method,
        array $parameters = [],
        string $httpMethod = 'post',
        string $apiVersion = '1'
    ): array {
        $headers = ['User-Agent' => 'Globitex PHP API Agent'];

        $headers['X-Nonce'] = $this->generateNonce();
        $headers['X-API-Key'] = $this->key;
        $headers['X-Signature'] = $this->generateSign($method, $parameters, $apiVersion);

        try {
            if ($httpMethod === 'post') {
                $response = $this->client->post($this->buildUrl($method, false, $apiVersion), [
                    'headers' => $headers,
                    'form_params' => $parameters,
                ]);
            } else {
                $response = $this->client->get($this->buildUrl($method, false, $apiVersion), [
                    'headers' => $headers,
                    'query' => $parameters,
                ]);
            }
        } catch (\Exception $exception) {
            if ($exception->getCode() === 404) {
                throw new GlobitexApiErrorException('Endpoint not found: ('.$this->buildUrl($method).')');
            } else {
                throw new GlobitexApiErrorException($exception);
            }
        }

        return $this->decodeResult(
            $response->getBody()->getContents()
        );
    }

    /**
     * Build url.
     *
     * @param bool $isPublic=false - indicator whether its a public call
     */
    protected function buildUrl(string $method, bool $isPublic = false, string $apiVersion = '1'): string
    {
        return static::API_URL.$this->buildPath($method, $isPublic, $apiVersion);
    }

    /**
     * Build path.
     *
     * @param bool $isPublic=false - indicator whether its a public call
     */
    protected function buildPath(string $method, bool $isPublic = false, string $apiVersion = '1'): string
    {
        $basePath = '/api/'.$apiVersion;
        // add public string if set
        if ($isPublic) {
            $basePath .= '/public';
        }

        return $basePath.'/'.$method;
    }

    /**
     * Compute globitex signature.
     *
     * uri = path [+ '?' + query]
     *
     * message = apikey + '&' + nonce + uri [+ ? + requestBody]
     *
     * signature = lower_case(hex(hmac_sha512(message.getBytes(‘UTF-8’), secret_key.getBytes(‘UTF-8’) )))
     */
    protected function generateSign(string $uri, array $parameters = [], string $apiVersion = '1'): string
    {
        $fullUri = $this->buildPath($uri, false, $apiVersion);

        // add queryString to uri (if parameters set and not empty)
        if (!empty($parameters) && $queryString = http_build_query($parameters, '', '&')) {
            $fullUri .= '?'.$queryString;
        }

        $message = $this->key.'&'.$this->nonce.$fullUri;

        return strtolower(hash_hmac('sha512', utf8_encode($message), utf8_encode($this->message_secret)));
    }

    /**
     * Generate a 64 bit nonce using a timestamp at microsecond resolution
     * string functions are used to avoid problems on 32 bit systems.
     */
    public function generateNonce(): string
    {
        $nonce = explode(' ', microtime());
        $this->nonce = $nonce[1].str_pad(substr($nonce[0], 2, 6), 6, '0');

        return $this->nonce;
    }

    /**
     * Decode json response from Globitex API.
     *
     * @param $response
     */
    protected function decodeResult($response): array
    {
        return \GuzzleHttp\json_decode(
            $response,
            true
        );
    }
}
