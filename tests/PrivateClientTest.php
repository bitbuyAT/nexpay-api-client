<?php

namespace bitbuyAT\Globitex\Tests;

use bitbuyAT\Globitex\Client;
use bitbuyAT\Globitex\Exceptions\GlobitexApiErrorException;
use bitbuyAT\Globitex\Objects\EuroPaymentParameters;
use bitbuyAT\Globitex\Objects\GBXUtilizationTransaction;
use bitbuyAT\Globitex\Objects\GBXUtilizationTransactionsCollection;
use bitbuyAT\Globitex\Objects\Transaction;
use bitbuyAT\Globitex\Objects\TransactionsCollection;
use GuzzleHttp\Client as HttpClient;
use PHPUnit\Framework\TestCase;

class PrivateClientTest extends TestCase
{
    protected $globitexService;
    protected $ticker;
    protected $orderBook;
    protected $trades;
    protected $assetPairs;

    protected function setUp(): void
    {
        parent::setUp();
        // instantiate service
        $this->globitexService = new Client(
            new HttpClient(),
            getenv('GLOBITEX_KEY') ?? null,
            getenv('GLOBITEX_MESSAGE_SECRET') ?? null,
            getenv('GLOBITEX_OUTGOING_SECRET') ?? null,
        );
    }

    public function testClientInstanceCanBeCreated(): void
    {
        $this->assertInstanceOf(Client::class, $this->globitexService);
    }

    public function testGetFirstAccount(): void
    {
        $accountBalances = $this->globitexService->getAccountBalance();
        $firstPair = $accountBalances->first();
        $data = $firstPair->getData();
        $this->assertArrayHasKey('account', $data);
        $this->assertArrayHasKey('main', $data);
        $this->assertArrayHasKey('balance', $data);
    }

    public function testGetBalanceOfFirstAccount(): void
    {
        $accountBalances = $this->globitexService->getAccountBalance();
        $firstPair = $accountBalances->first();
        $balances = $firstPair->getBalance();
        $firstBalance = $balances->first();
        $data = $firstBalance->getData();
        $this->assertArrayHasKey('currency', $data);
        $this->assertArrayHasKey('available', $data);
        $this->assertArrayHasKey('reserved', $data);
    }

    public function testGetCryptoTransactionFee(): void
    {
        $accountBalances = $this->globitexService->getAccountBalance();
        $firstPair = $accountBalances->first();
        $balances = $firstPair->getBalance();
        $firstBalance = $balances->first();
        $data = $firstBalance->getData();
        // only call this method if there is sufficient balance found
        if ($firstBalance->available() > 0) {
            $transactionFee = $this->globitexService->getCryptoTransactionFee($firstBalance->currency(), $firstBalance->available(), $firstPair->accountNumber());
            $data = $transactionFee->getData();
            $this->assertArrayHasKey('recommended', $data);
            $this->assertArrayHasKey('minimum', $data);
            $this->assertArrayHasKey('maximum', $data);
            $this->assertArrayHasKey('feeId', $data);
            $this->assertArrayHasKey('feeExpireTime', $data);
        } else {
            // otherwise expect exception if there are no funds left
            $this->expectException(GlobitexApiErrorException::class);
            $this->expectExceptionMessage('Invalid amount');
            $transactionFee = $this->globitexService->getCryptoTransactionFee($firstBalance->currency(), $firstBalance->available(), $firstPair->accountNumber());
        }
    }

    public function testGetTransactions(): void
    {
        $userTransactions = $this->globitexService->getTransactions();
        $firstTransaction = $userTransactions->first();
        $this->assertInstanceOf(TransactionsCollection::class, $userTransactions);
        // only do further tests if the user has a transaction
        if ($firstTransaction) {
            $data = $firstTransaction->getData();
            $this->assertInstanceOf(Transaction::class, $firstTransaction);
            $this->assertArrayHasKey('transactionCode', $data);
            $this->assertArrayHasKey('created', $data);
            $this->assertArrayHasKey('direction', $data);
            $this->assertArrayHasKey('paymentType', $data);
            $this->assertArrayHasKey('account', $data);
            $this->assertArrayHasKey('currency', $data);
            $this->assertArrayHasKey('amount', $data);
            $this->assertArrayHasKey('status', $data);

            // test various methods
            $this->assertEquals($firstTransaction->transactionCode(), $data['transactionCode']);
            $this->assertEquals($firstTransaction->created(), $data['created']);
            $this->assertEquals($firstTransaction->direction(), $data['direction']);
            $this->assertEquals($firstTransaction->paymentType(), $data['paymentType']);
        }
    }

    public function testGetGbxUtilizationTransactions(): void
    {
        $gbxUtilizationTransactions = $this->globitexService->getGBXUtilizationTransactions();
        $firstTransaction = $gbxUtilizationTransactions->first();
        $this->assertInstanceOf(GBXUtilizationTransactionsCollection::class, $gbxUtilizationTransactions);
        // only do further tests if the user has a transaction
        if ($firstTransaction) {
            $data = $firstTransaction->getData();
            $this->assertInstanceOf(GBXUtilizationTransaction::class, $firstTransaction);
            $this->assertArrayHasKey('transactionCode', $data);
            $this->assertArrayHasKey('created', $data);
            $this->assertArrayHasKey('direction', $data);
            $this->assertArrayHasKey('amount', $data);
            $this->assertArrayHasKey('currency', $data);
            $this->assertArrayHasKey('account', $data);
            $this->assertArrayHasKey('details', $data);

            // test various methods
            $this->assertEquals($firstTransaction->transactionCode(), $data['transactionCode']);
            $this->assertEquals($firstTransaction->created(), $data['created']);
            $this->assertEquals($firstTransaction->direction(), $data['direction']);
        }
    }

    public function testGetEuroAccountStatus(): void
    {
        $euroAccountBalances = $this->globitexService->getEuroAccountStatus();
        $firstPair = $euroAccountBalances->first();
        $data = $firstPair->getData();
        $this->assertArrayHasKey('iban', $data);
        $this->assertArrayHasKey('status', $data);
        $this->assertArrayHasKey('balance', $data);
    }

    public function testGetEuroPaymentHistory(): void
    {
        $euroPaymentHistory = $this->globitexService->getEuroPaymentHistory();
        $data = $euroPaymentHistory->getData();
        $this->assertArrayHasKey('debitTurnover', $data);
        $this->assertArrayHasKey('creditTurnover', $data);
        $this->assertArrayHasKey('balanceStart', $data);
        $this->assertArrayHasKey('balanceEnd', $data);
        $this->assertArrayHasKey('clientName', $data);
        $this->assertArrayHasKey('account', $data);
        $this->assertArrayHasKey('entries', $data);
    }

    public function testMakeEuroPayment(): void
    {
        $iban = getenv('GLOBITEX_ACCOUNT');

        $paymentParameters = new EuroPaymentParameters([
            'account' => $iban,
            'amount' => '1',
            'beneficiaryName' => 'Self',
            'beneficiaryAccount' => $iban,
            'beneficiaryReference' => 'Test',
            'useGbxForFee' => true,
            ]);

        $this->expectException(GlobitexApiErrorException::class);
        $this->expectExceptionMessage('Debtor and Creditor account number cannot be the same');
        $this->globitexService->makeEuroPayment($paymentParameters);
    }
}
