<?php

namespace bitbuyAT\Globitex\Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as HttpClient;
use bitbuyAT\Globitex\Client;
use bitbuyAT\Globitex\Objects\OrderBook;
use bitbuyAT\Globitex\Objects\Pair;
use bitbuyAT\Globitex\Objects\PairsCollection;
use bitbuyAT\Globitex\Objects\Trade;
use bitbuyAT\Globitex\Objects\TradesCollection;
use bitbuyAT\Globitex\Objects\Ticker;
use bitbuyAT\Globitex\Exceptions\GlobitexApiErrorException;

class PublicClientTest extends TestCase
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
        $this->globitexService = new Client(new HttpClient());
    }

    private function initTicker()
    {
        // get ticker
        $this->ticker = $this->globitexService->getTicker('BTCEUR');
    }

    private function initOrderBook()
    {
        // get order book
        $this->orderBook = $this->globitexService->getOrderBook('BTCEUR');
    }

    private function initTrades()
    {
        // get trades
        $this->trades = $this->globitexService->getTrades('BTCEUR');
    }

    private function initAssetPairs()
    {
        // get trades
        $this->assetPairs = $this->globitexService->getAssetPairs();
    }

    public function test_client_instance_can_be_created(): void
    {
        $this->assertInstanceOf(Client::class, $this->globitexService);
    }

    public function test_get_time_returns_timestamp(): void
    {
        $timestamp = $this->globitexService->getTime();
        $this->assertNotEmpty($timestamp);
    }

    public function test_ticker_instance_can_be_created_from_get_ticker(): void
    {
        $this->initTicker();
        $this->assertInstanceOf(Ticker::class, $this->ticker);
    }

    public function test_get_data_of_symbol_returns_array_with_all_keys(): void
    {
        $this->initTicker();
        $data = $this->ticker->getData();
        $this->assertEquals($data['symbol'], 'BTCEUR');
        $this->assertArrayHasKey('ask', $data);
        $this->assertArrayHasKey('bid', $data);
        $this->assertArrayHasKey('last', $data);
        $this->assertArrayHasKey('low', $data);
        $this->assertArrayHasKey('high', $data);
        $this->assertArrayHasKey('open', $data);
        $this->assertArrayHasKey('volume', $data);
        $this->assertArrayHasKey('volumeQuote', $data);
        $this->assertArrayHasKey('timestamp', $data);
    }

    public function test_get_bid_and_ask_price_of_ticker(): void
    {
        $this->initTicker();
        $data = $this->ticker->getData();
        $bidPrice = $this->ticker->bidPrice();
        $askPrice = $this->ticker->askPrice();

        $this->assertIsFloat($bidPrice);
        $this->assertEquals($data['bid'], $bidPrice);
        $this->assertIsFloat($askPrice);
        $this->assertEquals($data['ask'], $askPrice);
    }

    public function test_throw_error_if_pair_does_not_exist(): void
    {
        $this->expectException(GlobitexApiErrorException::class);
        $this->globitexService->getTicker('abcdef');
    }

    public function test_order_book_instance_can_be_created_from_get_order_book(): void
    {
        $this->initOrderBook();
        $this->assertInstanceOf(OrderBook::class, $this->orderBook);
    }

    public function test_get_data_of_order_book_returns_array_with_all_keys(): void
    {
        $this->initOrderBook();
        $data = $this->orderBook->getData();
        $this->assertArrayHasKey('bids', $data);
        $this->assertArrayHasKey('asks', $data);
    }

    public function test_get_bids_and_asks_order_book(): void
    {
        $this->initOrderBook();
        $data = $this->orderBook->getData();
        $bidPrices = $this->orderBook->getBids();
        $askPrices = $this->orderBook->getAsks();

        $this->assertIsArray($bidPrices);
        $this->assertEquals($data['bids'], $bidPrices);
        $this->assertIsArray($askPrices);
        $this->assertEquals($data['asks'], $askPrices);
    }

    public function test_trades_collection_instance_can_be_created_from_get_trades(): void
    {
        $this->initTrades();
        $this->assertInstanceOf(TradesCollection::class, $this->trades);
    }

    public function test_first_of_trades_returns_trade_object(): void
    {
        $this->initTrades();
        $firstTrade = $this->trades->first();
        $data = $firstTrade->getData();
        $this->assertInstanceOf(Trade::class, $firstTrade);
        $this->assertArrayHasKey('date', $data);
        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('tid', $data);
        $this->assertArrayHasKey('amount', $data);
    }

    public function test_pairs_collection_instance_can_be_created_from_get_asset_pairs(): void
    {
        $this->initAssetPairs();
        $this->assertInstanceOf(PairsCollection::class, $this->assetPairs);
    }

    public function test_first_of_pairs_returns_pair_object(): void
    {
        $this->initAssetPairs();
        $firstPair = $this->assetPairs->first();
        $data = $firstPair->getData();
        $this->assertInstanceOf(Pair::class, $firstPair);
        $this->assertArrayHasKey('symbol', $data);
        $this->assertArrayHasKey('priceIncrement', $data);
        $this->assertArrayHasKey('sizeIncrement', $data);
        $this->assertArrayHasKey('sizeMin', $data);
        $this->assertArrayHasKey('currency', $data);
        $this->assertArrayHasKey('commodity', $data);
    }
}
