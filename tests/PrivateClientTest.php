<?php

namespace bitbuyAT\Nexpay\Tests;

use bitbuyAT\Nexpay\Client;
use bitbuyAT\Nexpay\Exceptions\NexpayApiErrorException;
use bitbuyAT\Nexpay\Objects\EuroPaymentParameters;
use Carbon\Carbon;
use GuzzleHttp\Client as HttpClient;
use PHPUnit\Framework\TestCase;

class PrivateClientTest extends TestCase
{
    protected $nexpayService;
    protected $ticker;
    protected $orderBook;
    protected $trades;
    protected $assetPairs;

    protected function setUp(): void
    {
        parent::setUp();
        // instantiate service
        $this->nexpayService = new Client(
            new HttpClient(),
            getenv('NEXPAY_KEY') ?? null,
            getenv('NEXPAY_MESSAGE_SECRET') ?? null,
            getenv('NEXPAY_OUTGOING_SECRET') ?? null,
        );
    }

    public function testClientInstanceCanBeCreated(): void
    {
        $this->assertInstanceOf(Client::class, $this->nexpayService);
    }

    public function testGetEuroAccountStatus(): void
    {
        $euroAccountBalances = $this->nexpayService->getEuroAccountStatus();
        $firstPair = $euroAccountBalances->first();
        $data = $firstPair->getData();
        $this->assertArrayHasKey('iban', $data);
        $this->assertArrayHasKey('status', $data);
        $this->assertArrayHasKey('balance', $data);
    }

    public function testGetEuroPaymentHistory(): void
    {
        $euroPaymentHistory = $this->nexpayService->getEuroPaymentHistory();
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
        $iban = getenv('NEXPAY_ACCOUNT');
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
        $iban = getenv('NEXPAY_ACCOUNT');

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

        $this->expectException(NexpayApiErrorException::class);
        $this->expectExceptionMessage('Debtor and Creditor account number cannot be the same');
        echo $this->nexpayService->makeEuroPayment($paymentParameters);
    }

    public function testMakeEuroPaymentWithExternalSignature(): void
    {
        $iban = getenv('NEXPAY_ACCOUNT');
        $outgoingSecret = getenv('NEXPAY_OUTGOING_SECRET');
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

        $this->expectException(NexpayApiErrorException::class);
        $this->expectExceptionMessage('Beneficiary IBAN account is invalid');
        echo $this->nexpayService->makeEuroPayment($paymentParameters);
    }
}
