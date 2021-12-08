<?php

namespace bitbuyAT\Globitex\Tests;

use bitbuyAT\Globitex\Client;
use bitbuyAT\Globitex\Exceptions\GlobitexApiErrorException;
use bitbuyAT\Globitex\Objects\ExecutionReportsCollection;
use bitbuyAT\Globitex\Objects\GBXUtilizationTransaction;
use bitbuyAT\Globitex\Objects\GBXUtilizationTransactionsCollection;
use bitbuyAT\Globitex\Objects\GetMyTradesParameters;
use bitbuyAT\Globitex\Objects\MyTradesCollection;
use bitbuyAT\Globitex\Objects\NewOrderParameters;
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

    public function testGetNonOfMyTrades(): void
    {
        $accountBalances = $this->globitexService->getAccountBalance();
        $firstAccount = $accountBalances->first();
        $getMyTradesParams = new GetMyTradesParameters(
            [
                'account' => $firstAccount->accountNumber(),
                'maxResults' => 1,
                'from' => (int) Carbon::now()->getPreciseTimestamp(3), // this should result in no trades
            ]
        );
        $myTrades = $this->globitexService->getMyTrades($getMyTradesParams);
        $this->assertInstanceOf(MyTradesCollection::class, $myTrades);
        $this->assertEquals($myTrades->count(), 0);
    }

    public function testGetMyFirstTrade(): void
    {
        $accountBalances = $this->globitexService->getAccountBalance();
        $firstAccount = $accountBalances->first();
        $getMyTradesParams = new GetMyTradesParameters(
            [
                'account' => $firstAccount->accountNumber(),
                'maxResults' => 1,
            ]
        );
        $myTrades = $this->globitexService->getMyTrades($getMyTradesParams);
        $this->assertInstanceOf(MyTradesCollection::class, $myTrades);

        if ($myTrades->count() === 1) {
            $myTrade = $myTrades->first();
            $myTradeData = $myTrade->getData();
            $this->assertEquals($myTrade->account(), $getMyTradesParams->account());
            $this->assertArrayHasKey('tradeId', $myTradeData);
            $this->assertArrayHasKey('symbol', $myTradeData);
            $this->assertArrayHasKey('side', $myTradeData);
            $this->assertArrayHasKey('originalOrderId', $myTradeData);
            $this->assertArrayHasKey('execQuantity', $myTradeData);
            $this->assertArrayHasKey('timestamp', $myTradeData);
            $this->assertArrayHasKey('isLiqProvided', $myTradeData);
        } else {
            $this->assertEquals($myTrades->count(), 0);
        }
    }

    public function testPlaceNewInvalidOrder(): void
    {
        $accountBalances = $this->globitexService->getAccountBalance();
        $firstAccount = $accountBalances->first();

        $newOrderParameters = new NewOrderParameters(
            [
                'clientOrderId' => 'test_'.$this->globitexService->generateNonce(),
                'account' => $firstAccount->accountNumber(),
                'symbol' => 'BTCEUR',
                'side' => 'buy',
                'price' => '1',
                'quantity' => '0.0000001',
            ]
        );
        $this->expectException(GlobitexApiErrorException::class);
        $this->expectExceptionMessage('Order size less than minimum');
        $executionReport = $this->globitexService->placeNewOrder($newOrderParameters);
    }

    public function testPlaceNewOrderAndCancelIt(): void
    {
        $accountBalances = $this->globitexService->getAccountBalance();
        $firstAccount = $accountBalances->first();

        $newOrderParameters = new NewOrderParameters(
            [
                'clientOrderId' => 'test_'.$this->globitexService->generateNonce(),
                'account' => $firstAccount->accountNumber(),
                'symbol' => 'BTCEUR',
                'side' => 'buy',
                'price' => '1.00',
                'quantity' => '1.0000000',
            ]
        );

        $executionReport = $this->globitexService->placeNewOrder($newOrderParameters);
        $data = $executionReport->getData();
        $this->assertEquals($executionReport->orderStatus(), 'new');
        $this->assertArrayHasKey('orderId', $data);
        $this->assertEquals($executionReport->clientOrderId(), $newOrderParameters->getClientOrderId());
        $this->assertEquals($executionReport->symbol(), $newOrderParameters->getSymbol());
        $this->assertEquals($executionReport->side(), $newOrderParameters->getSide());
        $this->assertEquals($executionReport->price(), $newOrderParameters->getPrice());
        $this->assertEquals($executionReport->quantity(), $newOrderParameters->getQuantity());
        $this->assertEquals($executionReport->type(), 'limit');
        $this->assertEquals($executionReport->timeInForce(), 'GTC');
        $this->assertArrayHasKey('lastQuantity', $data);
        $this->assertArrayHasKey('lastPrice', $data);
        $this->assertArrayHasKey('leavesQuantity', $data);
        $this->assertArrayHasKey('cumQuantity', $data);
        $this->assertArrayHasKey('averagePrice', $data);
        $this->assertArrayHasKey('created', $data);
        $this->assertArrayHasKey('execReportType', $data);
        $this->assertArrayHasKey('timestamp', $data);
        $this->assertEquals($executionReport->account(), $newOrderParameters->getAccount());
        $this->assertEquals($executionReport->orderSource(), 'REST');
        $cancellationReport = $this->globitexService->cancelOrder(
            $executionReport->clientOrderId(),
            $executionReport->account()
        );
        $this->assertEquals($cancellationReport->orderId(), $executionReport->orderId());
        $this->assertEquals($cancellationReport->execReportType(), 'canceled');
        $this->assertEquals($cancellationReport->orderStatus(), 'canceled');
        $this->assertEquals($cancellationReport->symbol(), $executionReport->symbol());
        $this->assertArrayHasKey('lastQuantity', $data);
        $this->assertArrayHasKey('lastPrice', $data);
        $this->assertArrayHasKey('leavesQuantity', $data);
        $this->assertArrayHasKey('cumQuantity', $data);
        $this->assertArrayHasKey('averagePrice', $data);
        $this->assertArrayHasKey('timestamp', $data);
    }

    public function testCancelAllOrders(): void
    {
        $accountBalances = $this->globitexService->getAccountBalance();
        $firstAccount = $accountBalances->first();

        $executionReports = $this->globitexService->cancelAllOrders([
            'account' => $firstAccount->accountNumber(),
        ]);
        $this->assertInstanceOf(ExecutionReportsCollection::class, $executionReports);
        $executionReports = $this->globitexService->cancelAllOrders([
            'account' => $firstAccount->accountNumber(),
        ]);
        $this->assertEquals($executionReports->count(), 0);
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

    public function testGetCryptoCurrencyDepositAddressForBtcShouldWork(): void
    {
        $address = $this->globitexService->getCryptoCurrencyDepositAddress('BTC');
        $this->assertNotNull($address);
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
}
