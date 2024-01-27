<?php

namespace bitbuyAT\Globitex\Tests;

use bitbuyAT\Globitex\Client;
use bitbuyAT\Globitex\Exceptions\GlobitexApiErrorException;
use bitbuyAT\Globitex\Objects\EuroPaymentParameters;
use bitbuyAT\Globitex\Objects\GBXUtilizationTransaction;
use bitbuyAT\Globitex\Objects\GBXUtilizationTransactionsCollection;
use bitbuyAT\Globitex\Objects\Transaction;
use bitbuyAT\Globitex\Objects\TransactionsCollection;
use Carbon\Carbon;
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

    // public function testGetGbxUtilizationTransactions(): void
    // {
    //     $gbxUtilizationTransactions = $this->globitexService->getGBXUtilizationTransactions();
    //     $firstTransaction = $gbxUtilizationTransactions->first();
    //     $this->assertInstanceOf(GBXUtilizationTransactionsCollection::class, $gbxUtilizationTransactions);
    //     // only do further tests if the user has a transaction
    //     if ($firstTransaction) {
    //         $data = $firstTransaction->getData();
    //         $this->assertInstanceOf(GBXUtilizationTransaction::class, $firstTransaction);
    //         $this->assertArrayHasKey('transactionCode', $data);
    //         $this->assertArrayHasKey('created', $data);
    //         $this->assertArrayHasKey('direction', $data);
    //         $this->assertArrayHasKey('amount', $data);
    //         $this->assertArrayHasKey('currency', $data);
    //         $this->assertArrayHasKey('account', $data);
    //         $this->assertArrayHasKey('details', $data);

    //         // test various methods
    //         $this->assertEquals($firstTransaction->transactionCode(), $data['transactionCode']);
    //         $this->assertEquals($firstTransaction->created(), $data['created']);
    //         $this->assertEquals($firstTransaction->direction(), $data['direction']);
    //     }
    // }

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

    public function testSignatureMessageIsCorrect(): void
    {
        $iban = getenv('GLOBITEX_ACCOUNT');
        $timestampMs = (new Carbon())->getTimestampMs();
        $paymentParameters = new EuroPaymentParameters([
            'requestTime' => $timestampMs,
            'account' => $iban,
            'amount' => '1',
            'beneficiaryName' => 'Test Name',
            'beneficiaryAccount' => $iban,
            'beneficiaryReference' => 'Test',
            'externalPaymentId' => 'payTest',
            'useGbxForFee' => 'true',
            ]);

        $message = $paymentParameters->getTransactionSignatureMessage();

        $this->assertMatchesRegularExpression(
            '/^requestTime='.$timestampMs.'&account='.$iban.'&amount=1&beneficiaryName=Test Name&beneficiaryAccount='.$iban.'&beneficiaryReference=Test&useGbxForFee=true&externalPaymentId=payTest$/',
            $message
        );
    }

    public function testMakeEuroPayment(): void
    {
        $iban = getenv('GLOBITEX_ACCOUNT');

        $paymentParameters = new EuroPaymentParameters([
            'account' => $iban,
            'amount' => '1',
            'beneficiaryName' => 'John Doe',
            'beneficiaryAddress' => '123 Test St, 92109 San Diego, CA, USA',
            'beneficiaryAccount' => $iban,
            'beneficiaryReference' => 'Test',
            'externalPaymentId' => (new Carbon())->getTimestampMs().'-payTest',
            'useGbxForFee' => 'true',
            ]);

        $this->expectException(GlobitexApiErrorException::class);
        $this->expectExceptionMessage('Debtor and Creditor account number cannot be the same');
        echo $this->globitexService->makeEuroPayment($paymentParameters);
    }

    public function testMakeEuroPaymentWithExternalSignature(): void
    {
        $iban = getenv('GLOBITEX_ACCOUNT');
        $outgoingSecret = getenv('GLOBITEX_OUTGOING_SECRET');
        $requestTime = (new Carbon())->getTimestampMs();

        $paymentParameters = new EuroPaymentParameters([
            'requestTime' => $requestTime,
            'account' => $iban,
            'amount' => '1',
            'beneficiaryName' => 'John Doe',
            'beneficiaryAddress' => '123 Test St, 92109 San Diego, CA, USA',
            'beneficiaryAccount' => 'XX1234567890',
            'beneficiaryReference' => 'Test',
            'externalPaymentId' => $requestTime.'payTest',
            'useGbxForFee' => 'true',
            ]);
        $paymentParameters->generateTransactionSignature($outgoingSecret);

        $this->expectException(GlobitexApiErrorException::class);
        $this->expectExceptionMessage('Beneficiary IBAN account is invalid');
        echo $this->globitexService->makeEuroPayment($paymentParameters);
    }
}
