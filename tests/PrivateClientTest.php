<?php

namespace bitbuyAT\Globitex\Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as HttpClient;
use bitbuyAT\Globitex\Client;
use bitbuyAT\Globitex\Objects\UserTransaction;
use bitbuyAT\Globitex\Objects\UserTransactionsCollection;
use bitbuyAT\Globitex\Exceptions\GlobitexApiErrorException;

class PrivateClientTest extends TestCase
{
    protected $globitexService;
    protected $ticker;
    protected $orderBook;
    protected $transactions;
    protected $assetPairs;

    protected function setUp(): void
    {
        parent::setUp();
        // instantiate service
        $this->globitexService = new Client(
            new HttpClient(),
            getenv('GLOBITEX_KEY') ?? null,
            getenv('GLOBITEX_SECRET') ?? null,
            getenv('GLOBITEX_CUSTOMER_ID') ?? null
        );
    }

    public function test_client_instance_can_be_created(): void
    {
        $this->assertInstanceOf(Client::class, $this->globitexService);
    }

    public function test_get_account_balance(): void
    {
        $accountBalance = $this->globitexService->getAccountBalance();
        $data = $accountBalance->getData();
        $this->assertArrayHasKey('btc_balance', $data);
        $this->assertArrayHasKey('bch_balance', $data);
        $this->assertArrayHasKey('eth_balance', $data);
        $this->assertArrayHasKey('ltc_balance', $data);
        $this->assertArrayHasKey('eur_balance', $data);
        $this->assertArrayHasKey('usd_balance', $data);
        $this->assertArrayHasKey('xrp_balance', $data);
        $this->assertArrayHasKey('btc_reserved', $data);
        $this->assertArrayHasKey('bch_reserved', $data);
        $this->assertArrayHasKey('eth_reserved', $data);
        $this->assertArrayHasKey('ltc_reserved', $data);
        $this->assertArrayHasKey('eur_reserved', $data);
        $this->assertArrayHasKey('usd_reserved', $data);
        $this->assertArrayHasKey('xrp_reserved', $data);
        $this->assertArrayHasKey('btc_available', $data);
        $this->assertArrayHasKey('bch_available', $data);
        $this->assertArrayHasKey('eth_available', $data);
        $this->assertArrayHasKey('ltc_available', $data);
        $this->assertArrayHasKey('eur_available', $data);
        $this->assertArrayHasKey('usd_available', $data);
        $this->assertArrayHasKey('xrp_available', $data);
        $this->assertArrayHasKey('bchbtc_fee', $data);
        $this->assertArrayHasKey('bcheur_fee', $data);
        $this->assertArrayHasKey('bchusd_fee', $data);
        $this->assertArrayHasKey('btcusd_fee', $data);
        $this->assertArrayHasKey('btceur_fee', $data);
        $this->assertArrayHasKey('ethbtc_fee', $data);
        $this->assertArrayHasKey('etheur_fee', $data);
        $this->assertArrayHasKey('ethusd_fee', $data);
        $this->assertArrayHasKey('ltcbtc_fee', $data);
        $this->assertArrayHasKey('ltceur_fee', $data);
        $this->assertArrayHasKey('ltcusd_fee', $data);
        $this->assertArrayHasKey('eurusd_fee', $data);
        $this->assertArrayHasKey('xrpusd_fee', $data);
        $this->assertArrayHasKey('xrpeur_fee', $data);
        $this->assertArrayHasKey('xrpbtc_fee', $data);

        // test various methods
        $this->assertEquals($accountBalance->eurBalance(), $data['eur_balance']);
        $this->assertEquals($accountBalance->ethReserved(), $data['eth_reserved']);
        $this->assertEquals($accountBalance->btcAvailable(), $data['btc_available']);
        $this->assertEquals($accountBalance->btceurFee(), $data['btceur_fee']);
    }

    public function test_get_user_transactions(): void
    {
        $userTransactions = $this->globitexService->getUserTransactions('btceur');
        $firstUserTransaction = $userTransactions->first();
        $this->assertInstanceOf(UserTransactionsCollection::class, $userTransactions);
        // only do further tests if the user has transactions
        if ($firstUserTransaction) {
            $data = $firstUserTransaction->getData();
            $this->assertInstanceOf(UserTransaction::class, $firstUserTransaction);
            $this->assertArrayHasKey('datetime', $data);
            $this->assertArrayHasKey('id', $data);
            $this->assertArrayHasKey('type', $data);
            $this->assertArrayHasKey('usd', $data);
            $this->assertArrayHasKey('eur', $data);
            $this->assertArrayHasKey('btc', $data);
            $this->assertArrayHasKey('btc_eur', $data);
            $this->assertArrayHasKey('fee', $data);
            $this->assertArrayHasKey('order_id', $data);
            $this->assertArrayHasKey('btc', $data);

            // test various methods
            $this->assertEquals($firstUserTransaction->getDatetime(), $data['datetime']);
            $this->assertEquals($firstUserTransaction->eur(), $data['eur']);
            $this->assertEquals($firstUserTransaction->btceurExchangeRate(), $data['btc_eur']);
            $this->assertEquals($firstUserTransaction->getFee(), $data['fee']);
        }
    }

    public function test_throw_error_on_invalid_params_when_getting_user_transactions(): void
    {
        $this->expectException(GlobitexApiErrorException::class);
        $this->expectExceptionMessage('Invalid offset.');
        $this->globitexService->getUserTransactions('btceur', -1);
    }

    public function test_it_should_get_all_user_transactions_if_pair_is_empty(): void
    {
        $userTransactions = $this->globitexService->getUserTransactions();
        $this->assertInstanceOf(UserTransactionsCollection::class, $userTransactions);
    }
}
