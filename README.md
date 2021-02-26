# Description

[![Latest Version on Packagist](https://img.shields.io/packagist/v/frog/cooperative-bank-sdk.svg?style=flat-square)](https://packagist.org/packages/frog/cooperative-bank-sdk)
[![Build Status](https://img.shields.io/travis/frog/cooperative-bank-sdk/master.svg?style=flat-square)](https://travis-ci.com/github/frogtechnologies/cooperative-bank-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/frog/cooperative-bank-sdk.svg?style=flat-square)](https://packagist.org/packages/frog/cooperative-bank-sdk)

An SDK to aid interaction with the Cooperative Bank API

## Installation

You can install the package via composer:

```bash
composer require frog/cooperative-bank-sdk
```

### Setup environment variables

```bash
COOP_CONSUMER_KEY=
COOP_CONSUMER_SECRET=
COOP_API_BASE_URL=http://developer.co-opbank.co.ke:8280#Testing
# COOP_API_BASE_URL=https://developer.co-opbank.co.ke:8243 # Production
```

## Usage

```php
use FROG\CooperativeBankSdk\CooperativeBankSdk;
use FROG\CooperativeBankSdk\CoopUtils;

$coop_sdk = new CooperativeBankSDK();

/**
 * Generate an access token
 * NB: Not necessarily used anywhere as the SDK uses it internally to send requests
 *
 */
$access_token = $coop_sdk->generate_access_token();
print_r($access_token);
// {
//   "access_token": "xxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
//   "scope": "am_application_scope default",
//   "token_type": "Bearer",
//   "expires_in": 3600
// }

/**
 * Check for the account balance
 *
 * The message reference is optional in the case the client has an internal unique referencing system
 * The SDK implements the php random_bytes function internally to generate a unique number
 * @param string $account_number the cooperative bank account number
 * @param void|string $message_reference an optional message reference
 */
// Option 1: Pass in your unique internal message reference (UUID's don't work though)
$balance = $coop_sdk->check_account_balance(
    "account-number",
    "message-reference",
);

// Or you could use the utility from the package to get a unique message reference before the request is made
$balance = $coop_sdk->check_account_balance(
    "account-number",
    CoopUtils::generate_message_reference(),
);

// Or let the sdk generate one internally and access the message reference from the response. 
// All responses usually have message references
$balance = $coop_sdk->check_account_balance(
    "account-number",
);
print_r($balance);
// {
//   "MessageReference": "MESSAGE_REFERENCE_WILL_APPEAR_HERE",
//   "MessageDateTime": "2021-02-26 15:18:58",
//   "MessageCode": "0",
//   "MessageDescription": "Success",
//   "AccountNumber": "36001873000",
//   "AccountName": "JOE K. DOE",
//   "Currency": "USD",
//   "ProductName": "CURRENT ACCOUNT",
//   "ClearedBalance": "13706.07",
//   "BookedBalance": "75391.31",
//   "BlockedBalance": "27066.64",
//   "AvailableBalance": "21962.96",
//   "ArrearsAmount": "12645.56",
//   "BranchName": "GIGIRI MALL",
//   "BranchSortCode": "11151",
//   "AverageBalance": "27339.95",
//   "UnclearedBalance": "26658.48",
//   "ODLimit": "17614.28",
//   "CreditLimit": "23181.53"
// }

/**
 * Get the full & mini statement of an account
 * @param string $account_number an account number from a cooperative bank branch
 * @param string $start_date the date at which the earliest transaction is to be fetched
 * @param string $end_date the date at which the latest transction is to be fetched
 * @param string $message_reference a unique client generated string to be a reference when a request is sent
 * The message reference is optional in the case the client has an internal unique referencing system
 * The SDK implements the php random_bytes function internally to generate a unique number
*/
$statement = $coop_sdk->get_account_full_statement(
    "account-number",
    "start-date",
    "end-date",
    "message-reference",
);
$statement = $coop_sdk->get_account_mini_statement(
    "account-number",
    "start-date",
    "end-date",
    "message-reference",
);
print_r($statement);
// {
//   "MessageReference": "40ca18c6765086089a1",
//   "MessageDateTime": "2021-02-26 10:27:16",
//   "MessageCode": "0",
//   "MessageDescription": "Success",
//   "AccountNumber": "36001873000",
//   "AccountName": "JOE K. DOE",
//   "Transactions": [
//     {
//       "TransactionID": "50a603a3-2",
//       "TransactionDate": "2021-03-07 23:52:28",
//       "ValueDate": "2021-03-07 23:52:28",
//       "Narration": "Electricity payment",
//       "TransactionType": "D",
//       "ServicePoint": "POS-P0917",
//       "TransactionReference": "8be9b10d-4",
//       "CreditAmount": "0.00",
//       "DebitAmount": "72869.62",
//       "RunningClearedBalance": "22749.45",
//       "RunningBookBalance": "22749.45",
//       "DebitLimit": "0.00",
//       "LimitExpiryDate": "2021-03-07 00:00:00"
//     },
//     ...
//   ]
// }

/**
 * Retrieves the transactions of the specified account
 *
 * @param string $message_reference a unique client generated string to be a reference when a request is sent
 * @param string $account_number an account number from a cooperative bank branch
 * @param string $no_of_transactions a number of transactions to be retrieved (1 - 500)
 */
$transactions = $coop_sdk->get_account_transactions(
    "account-number",
    "no-of-transactions",
    "message-reference",
);
print_r($transactions);
// {
//   "MessageReference": "40ca18c6765086089a1",
//   "MessageDateTime": "2021-02-26 15:18:04",
//   "MessageCode": "0",
//   "MessageDescription": "Success",
//   "AccountNumber": "36001873000",
//   "AccountName": "JOE K. DOE",
//   "Transactions": [
//     {
//       "TransactionID": "fa24e510-c",
//       "TransactionDate": "2021-02-28 20:28:45",
//       "ValueDate": "2021-02-28 20:28:45",
//       "Narration": "Cash Deposit",
//       "TransactionType": "C",
//       "ServicePoint": "AGENT-10001",
//       "TransactionReference": "37e4a38d-9",
//       "CreditAmount": "97705.30",
//       "DebitAmount": "0.00",
//       "RunningClearedBalance": "172774.05",
//       "RunningBookBalance": "172774.05",
//       "DebitLimit": "0.00",
//       "LimitExpiryDate": "2021-02-27 00:00:00"
//     },
//     ...
//   ]
// }

/**
     * Retrieves the status of the specified transaction fetched by a message id
     * @param string $message_reference a unique client generated string to be a reference when a request is sent
     */
$status = $coop_sdk->check_transaction_status("message-reference");
print_r($status);
// {
//     "messageReference": "40ca18c6765086089a1",
//     "messageDateTime": "2021-02-26 15:23:32",
//     "messageCode": "0",
//     "messageDescription": "Full Success",
//     "source": {
//         "accountNumber": "36001873000",
//         "amount": "777",
//         "transactionCurrency": "KES",
//         "narration": "Supplier Payment",
//         "responseCode": "0",
//         "responseDescription": "Success"
//     },
//     "destination": {
//         "referenceNumber": "40ca18c6765086089a1_1",
//         "accountNumber": "54321987654321",
//         "amount": "777",
//         "transactionCurrency": "KES",
//         "narration": "Electricity Payment",
//         "transactionID": "cfea0f9d-c",
//         "responseCode": "0",
//         "responseDescription": "Success"
//     }
// }

/**
 * Retrieves the validity of the specified account
 * 
 * @param string $account_number an account number from a cooperative bank branch
 * @param string $message_reference a unique client generated string to be a reference when a request is sent
 */
$status = $coop_sdk->validate_account("account-number", "message-reference");
print_r($status);
// {
//   "MessageReference": "BTVd6xr7vEX97hWgNqM",
//   "MessageDateTime": "2021-02-26 16:36:39",
//   "MessageCode": "0",
//   "MessageDescription": "VALID ACCOUNT NUMBER"
// }

/**
 * Retrieves the exhange rate for the day for a specific account
 * 
 * @param string $from_currency a valid international currency
 * @param string $to_currency a valid international currency
 * @param string $message_reference a unique client generated string to be a reference when a request is sent
 */
$rate = $coop_sdk->exchange_rate("from-currency", "to-currency", "message-reference");
print_r($rate);
// {
//   "MessageReference": "40ca18c6765086089a1",
//   "MessageDateTime": "2021-02-26 20:31:31",
//   "MessageCode": "0",
//   "MessageDescription": "Success",
//   "FromCurrencyCode": "KES",
//   "ToCurrencyCode": "USD",
//   "RateType": "SPOT",
//   "Rate": "104.35",
//   "Tolerance": "6",
//   "MultiplyDivide": "D"
// }
```

### Testing

`NB`: The primary development machine is windows, ensure to change the path in the composer.json script section to conform to the operating system

Tests are powered by [PestPHP](https://pestphp.com/)

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email milleradulu@gmail.com instead of using the issue tracker.

## Credits

-   [Miller Adulu](https://github.com/milleradulu)
-   [All Contributors](../../contributors)

## License

The MIT. Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).
