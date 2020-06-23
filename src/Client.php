<?php

namespace bitbuyAT\Globitex;

use bitbuyAT\Globitex\Contracts\Client as ClientContract;
use bitbuyAT\Globitex\Exceptions\GlobitexApiErrorException;
use bitbuyAT\Globitex\Objects\Balance;
use bitbuyAT\Globitex\Objects\OrderBook;
use bitbuyAT\Globitex\Objects\Pair;
use bitbuyAT\Globitex\Objects\PairsCollection;
use bitbuyAT\Globitex\Objects\Ticker;
use bitbuyAT\Globitex\Objects\Transaction;
use bitbuyAT\Globitex\Objects\TransactionsCollection;
use bitbuyAT\Globitex\Objects\UserTransaction;
use bitbuyAT\Globitex\Objects\UserTransactionsCollection;
use GuzzleHttp\ClientInterface as HttpClient;

class Client implements ClientContract
{
    const API_URL = 'https://www.globitex.net/api';
    const API_VERSION = 'v2';

    /**
     * API key.
     *
     * @var string
     */
    protected $key;

    /**
     * API secret.
     *
     * @var string
     */
    protected $secret;

    /**
     * API secret.
     *
     * @var string
     */
    protected $customerId;

    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @param HttpClient $client
     * @param string     $key      API key
     * @param string     $secret   API secret
     * @param string     $customerId customer id (can be found in account balance)
     */
    public function __construct(HttpClient $client, ?string $key = '', ?string $secret = '', ?string $customerId = '')
    {
        $this->client = $client;
        $this->key = $key;
        $this->secret = $secret;
        $this->customerId = $customerId;
    }

    /**
     * Get ticker information.
     *
     * @param string $pair
     *
     * @return Ticker
     *
     * @throws GlobitexApiErrorException
     */
    public function getTicker(string $pair): Ticker
    {
        $result = $this->publicRequest('ticker', $pair);

        return new Ticker($result);
    }

    /**
     * Get hourly ticker information.
     *
     * @param string $pair
     *
     * @return Ticker
     *
     * @throws GlobitexApiErrorException
     */
    public function getHourlyTicker(string $pair): Ticker
    {
        $result = $this->publicRequest('ticker_hour', $pair);

        return new Ticker($result);
    }

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
    public function getOrderBook(string $pair, ?int $group = 1): OrderBook
    {
        $result = $this->publicRequest('order_book', $pair, ['group' => $group]);

        return new OrderBook($result);
    }

    /**
     * Get current transactions.
     *
     * @param string $pair
     * @param string $time The time interval from which we want the transactions to be returned.
     *                     Possible values are minute, hour (default) or day.
     *
     * @return TransactionsCollection|Transaction[]
     *
     * @throws GlobitexApiErrorException
     */
    public function getTransactions(string $pair, ?string $time = 'hour'): TransactionsCollection
    {
        $result = $this->publicRequest('transactions', $pair, ['time' => $time]);

        return (new TransactionsCollection($result))->map(function ($data) {
            return new Transaction($data);
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
        $result = $this->publicRequest('trading-pairs-info');

        return (new PairsCollection($result))->map(function ($data) {
            return new Pair($data);
        });
    }

    /**
     * Get account balance.
     *
     * @return Balance
     *
     * @throws GlobitexApiErrorException
     */
    public function getAccountBalance(): Balance
    {
        $result = $this->privateRequest('balance', []);

        return new Balance($result);
    }

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
    public function getUserTransactions(?string $pair = null, ?int $offset = 0, ?int $limit = 100, ?string $sort = 'desc', ?int $sinceTimestamp = null): UserTransactionsCollection
    {
        $params = [
            'offset' => $offset,
            'limit' => $limit,
            'sort' => $sort,
            'sinceTimestamp' => $sinceTimestamp,
        ];

        $result = $this->privateRequest('user_transactions/'.$pair, $params);

        if (isset($result['status']) && $result['status'] === 'error') {
            throw new GlobitexApiErrorException($result['reason']);
        }

        return (new UserTransactionsCollection($result))->map(function ($data) {
            return new UserTransaction($data);
        });
    }

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
    public function publicRequest(string $method, string $path = '', $parameters = []): array
    {
        $headers = ['User-Agent' => 'Globitex PHP API Agent'];

        try {
            $response = $this->client->get($this->buildUrl($method).'/'.$path, [
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
    public function privateRequest(string $method, array $parameters = []): array
    {
        $headers = ['User-Agent' => 'Globitex PHP API Agent'];

        $parameters['nonce'] = $this->generateNonce();
        $parameters['key'] = $this->key;
        $parameters['signature'] = $this->generateSign();

        try {
            $response = $this->client->post($this->buildUrl($method).'/', [
                'headers' => $headers,
                'form_params' => $parameters,
                'verify' => true,
            ]);
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
     * @param string $method
     *
     * @return string
     */
    protected function buildUrl(string $method): string
    {
        return static::API_URL.$this->buildPath($method);
    }

    /**
     * Build path.
     *
     * @param string $method
     *
     * @return string
     */
    protected function buildPath(string $method): string
    {
        return '/'.static::API_VERSION.'/'.$method;
    }

    /**
     * Compute globitex signature
     * message = nonce + customer_id + api_key
     * signature = hmac.new(
     *   API_SECRET,
     *   msg=message,
     *   digestmod=hashlib.sha256
     * ).hexdigest().upper().
     *
     * @return string
     */
    protected function generateSign(): string
    {
        $message = $this->nonce.$this->customerId.$this->key;

        return strtoupper(hash_hmac('sha256', $message, $this->secret));
    }

    /**
     * Generate a 64 bit nonce using a timestamp at microsecond resolution
     * string functions are used to avoid problems on 32 bit systems.
     *
     * @return string
     */
    protected function generateNonce(): string
    {
        $nonce = explode(' ', microtime());
        $this->nonce = $nonce[1].str_pad(substr($nonce[0], 2, 6), 6, '0');

        return $this->nonce;
    }

    /**
     * Decode json response from Globitex API.
     *
     * @param $response
     *
     * @return array
     */
    protected function decodeResult($response): array
    {
        return \GuzzleHttp\json_decode(
            $response,
            true
        );
    }
}
