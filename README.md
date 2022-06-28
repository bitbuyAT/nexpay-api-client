_This project is only supporting a selected choice of api calls to the globitex api, feel free to contribute!_

# globitex-api-client

Client for Globitex.net HTTP API with support for Laravel.
API docs: https://globitex.com/api/

# Install

`composer require bitbuy-at/globitex-api-client`

## Laravel

### Laravel 5.5+

If you're using Laravel 5.5 or above, the package will automatically register the Globitex provider and facade.

### Laravel 5.4 and below

Add `bitbuyAT\Globitex\GlobitexServiceProvider` to the providers array in your `config/app.php`:

```php
'providers' => [
    // Other service providers...

    bitbuyAT\Globitex\GlobitexServiceProvider::class,
],
```

If you want to use the facade interface, you can use the facade class when needed:

```
use bitbuyAT\Globitex\Facade\Globitex;
```

Or add an alias in your `config/app.php`:

```php
'aliases' => [
    ...
    'Globitex' => bitbuyAT\Globitex\Facade\Globitex::class,
],
```

## Configuration

You can update your .env file with the following settings (only needed for private calls):

```
GLOBITEX_KEY=key
GLOBITEX_MESSAGE_SECRET=message_secret
GLOBITEX_OUTGOING_SECRET=outgoin_secret
```

## Usage

### Get current prices

```php
use bitbuyAT\Globitex\Facade\Globitex;

$tradingPair = 'BTCEUR';
$prices = Globitex::getTicker($tradingPair);
$prices->getData();
$prices->askPrice();
$prices->bidPrice();
```

_More examples can be found in the `/tests` folder._

### Supported Methods

All currently supported methods with params explanation can be found in the client interface (`src/Contracts/Client.php`).

#### Market Data methods (public)

- [Get Time](https://globitex.com/api/#restGetTime): `Globitex::getTime(): int`
- [Get Symbols](https://globitex.com/api/#restGetSymbols): `Globitex::getAssetPairs(): PairsCollection`
- [Get Order Book For Symbol](https://globitex.com/api/#restGetOrderBook): `Globitex::getOrderBook(string $pair): OrderBook`

#### Trading methods (private)

- [Place New Order](https://globitex.com/api/#PlaceNewOrder): `Globitex::placeNewOrder(NewOrderParameters $newOrderParams): ExecutionReport`
- [Cancel Order](https://globitex.com/api/#CancelOrder): `Globitex::cancelOrder(string $clientOrderId, string $account): ExecutionReport`
- [Cancel All Orders](https://globitex.com/api/#CancelAllOrders): `Globitex::cancelAllOrders(array $params = []): ExecutionReport`
- [Get My Trades](https://globitex.com/api/#GetMyTrades): `Globitex::getMyTrades(GetMyTradesParameters $getMyTradesParams): MyTradesCollection`

#### Payment Data methods (private)

- [Get Balance](https://globitex.com/api/#GetBalance): `Globitex::getAccountBalance(): AccountsCollection`
- [Get Crypto Transaction Fee](https://globitex.com/api/#CryptoAddressGet): `Globitex::getCryptoTransactionFee(string $currency, string $amount, string $account): CryptoTransactionFee`
- [Get Cryptocurrency Deposit Address](https://globitex.com/api/#CryptoAddressGet): `Globitex::getCryptoCurrencyDepositAddress(string $currency, ?string $account = null): string`
- [Get Transaction List](https://globitex.com/api/#GetTransactionList): `Globitex::getTransactions(array $params = []): TransactionsCollection`
- [Get GBX (Globitex Token) Utilization List](https://globitex.com/api/#GbxUtilizationList): `Globitex::getGBXUtilizationTransactions(array $params = []): GBXUtilizationTransactionsCollection`

#### EURO wallet methods (private)

- [Get Account Status](https://globitex.com/api/#GetAccountStatus): `Globitex::getEuroAccountStatus(): EuroAccountsCollection`
- [Get Payment History](https://globitex.com/api/#GetPaymentHistory): `Globitex:: getEuroPaymentHistory(string $fromDate = null, string $toDate = null, string $account = null): EuroPaymentHistory`

Do you need any further method, which is not listed here? Just open an issue with the required method or even better open a PR to speed things up!

# Contributing

Want to contribute? Great!

Create a new issue first, describing the feature or bug.

Just fork our code, make your changes, then let us know and we will review it.

1. Fork it.
2. Create a feature branch (git checkout -b my_feature)
3. Commit your changes (git commit -m "Added My Feature")
4. Push to the branch (git push origin my_feature)
5. Open a [Pull Request](https://github.com/bitbuyAT/globitex-api-client/compare)
6. Enjoy and wait ;)

We are constantly updating and improving our code. We hope it can be for the benefit of the entire community.

# Who are we?

This package is maintained by bitbuy GmbH. We develop crypto and blockchain related software and operate a Vienna-based Bitcoin Store (bitcoin.wien). Feel free to visit our website for more info, if you're based in Vienna and looking to [buy Bitcoin for cash](https://www.bitcoin.wien/buy/bitcoin/). You can also [sell your Bitcoin for cash](https://www.bitcoin.wien/sell/bitcoin/) in our store.

# License

MIT License

Please check [LICENSE.txt](https://github.com/bitbuyAT/globitex-api-client/blob/master/LICENSE.txt)
