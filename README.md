_This project is only supporting a selected choice of api calls to the nexpay api, feel free to contribute!_

# nexpay-api-client

Client for Nexpay HTTP API with support for Laravel.
API docs: https://paynexpay.com/api

# Install

`composer require bitbuy-at/nexpay-api-client`

## Laravel

If you're using Laravel, the package will automatically register the Nexpay provider and facade.


## Configuration

You can update your .env file with the following settings (only needed for private calls):

```
NEXPAY_KEY=key
NEXPAY_MESSAGE_SECRET=message_secret
NEXPAY_OUTGOING_SECRET=outgoing_secret
```

## Usage

### Get current prices

```php
use bitbuyAT\Nexpay\Facade\Nexpay;

$tradingPair = 'BTCEUR';
$prices = Nexpay::getTicker($tradingPair);
$prices->getData();
$prices->askPrice();
$prices->bidPrice();
```

_More examples can be found in the `/tests` folder._

### Supported Methods

All currently supported methods with params explanation can be found in the client interface (`src/Contracts/Client.php`).

#### EURO wallet methods (private)

- [Get Account Information](https://paynexpay.com/api/#get-account-information): `Nexpay::getEuroAccountStatus(): EuroAccountsCollection`
- [Get Account History](https://paynexpay.com/api/#get-account-history): `Nexpay::getEuroPaymentHistory(string $fromDate = null, string $toDate = null, string $account = null): EuroPaymentHistory`
- [Make New Payment](https://paynexpay.com/api/#make-new-payment): `Nexpay::makeEuroPayment(EuroPaymentParameters $params, string $transactionSignature = null): EuroPaymentStatus`

### Old methods

Methods that are no longer available since the Nexpay exchange was discontinued

#### Market Data methods (public)

- [Get Time](https://globitex.com/api/#restGetTime): `Nexpay::getTime(): int`
- [Get Symbols](https://globitex.com/api/#restGetSymbols): `Nexpay::getAssetPairs(): PairsCollection`
- [Get Order Book For Symbol](https://globitex.com/api/#restGetOrderBook): `Nexpay::getOrderBook(string $pair): OrderBook`

#### Trading methods (private)

- [Place New Order](https://globitex.com/api/#PlaceNewOrder): `Nexpay::placeNewOrder(NewOrderParameters $newOrderParams): ExecutionReport`
- [Cancel Order](https://globitex.com/api/#CancelOrder): `Nexpay::cancelOrder(string $clientOrderId, string $account): ExecutionReport`
- [Cancel All Orders](https://globitex.com/api/#CancelAllOrders): `Nexpay::cancelAllOrders(array $params = []): ExecutionReport`
- [Get My Trades](https://globitex.com/api/#GetMyTrades): `Nexpay::getMyTrades(GetMyTradesParameters $getMyTradesParams): MyTradesCollection`

#### Payment Data methods (private)

- [Get Balance](https://globitex.com/api/#GetBalance): `Nexpay::getAccountBalance(): AccountsCollection`
- [Get Crypto Transaction Fee](https://globitex.com/api/#CryptoAddressGet): `Nexpay::getCryptoTransactionFee(string $currency, string $amount, string $account): CryptoTransactionFee`
- [Get Cryptocurrency Deposit Address](https://globitex.com/api/#CryptoAddressGet): `Nexpay::getCryptoCurrencyDepositAddress(string $currency, ?string $account = null): string`
- [Get Transaction List](https://globitex.com/api/#GetTransactionList): `Nexpay::getTransactions(array $params = []): TransactionsCollection`
- [Get GBX (Nexpay Token) Utilization List](https://globitex.com/api/#GbxUtilizationList): `Nexpay::getGBXUtilizationTransactions(array $params = []): GBXUtilizationTransactionsCollection`

Do you need any further method, which is not listed here? Just open an issue with the required method or even better open a PR to speed things up!

# Contributing

Want to contribute? Great!

Create a new issue first, describing the feature or bug.

Just fork our code, make your changes, then let us know and we will review it.

1. Fork it.
2. Create a feature branch (git checkout -b my_feature)
3. Commit your changes (git commit -m "Added My Feature")
4. Push to the branch (git push origin my_feature)
5. Open a [Pull Request](https://github.com/bitbuyAT/nexpay-api-client/compare)
6. Enjoy and wait ;)

We are constantly updating and improving our code. We hope it can be for the benefit of the entire community.

# Who are we?

This package is maintained by bitbuy GmbH. We develop crypto and blockchain related software and operate a Vienna-based Bitcoin Store (bitcoin.wien). Feel free to visit our website for more info, if you're based in Vienna and looking to [buy Bitcoin for cash](https://www.bitcoin.wien/buy/bitcoin/). You can also [sell your Bitcoin for cash](https://www.bitcoin.wien/sell/bitcoin/) in our store.

# License

MIT License

Please check [LICENSE.txt](https://github.com/bitbuyAT/nexpay-api-client/blob/master/LICENSE.txt)
