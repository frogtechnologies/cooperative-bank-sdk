<?php

namespace FROG\CooperativeBankSdk\Tests\Unit\AccountTransaction;

class AccountTransactionsResponse
{
    static public function auth_success(): string
    {
        return '{"access_token":"4fbb7b9e-9c73-3155-ac14-c14fe744d104","scope":"am_application_scope default","token_type":"Bearer","expires_in":3600}';
    }

    static public function success(): string
    {
        return '{
            "MessageReference": "40ca18c6765086089a1",
            "MessageDateTime": "2021-02-26 14:46:06",
            "MessageCode": "0",
            "MessageDescription": "Success",
            "AccountNumber": "36001873000",
            "AccountName": "JOE K. DOE",
            "Transactions": [
              {
                "TransactionID": "6e9f1870-2",
                "TransactionDate": "2021-02-28 19:19:38",
                "ValueDate": "2021-02-28 19:19:38",
                "Narration": "Payment Received from xyz",
                "TransactionType": "C",
                "ServicePoint": "IPSL",
                "TransactionReference": "a58a0dbf-9",
                "CreditAmount": "2528.51",
                "DebitAmount": "0.00",
                "RunningClearedBalance": "21514.40",
                "RunningBookBalance": "21514.40",
                "DebitLimit": "0.00",
                "LimitExpiryDate": "2021-02-27 00:00:00"
              },
              {
                "TransactionID": "ae4d9a7a-c",
                "TransactionDate": "2021-02-27 17:25:56",
                "ValueDate": "2021-02-27 17:25:56",
                "Narration": "Payment for goods",
                "TransactionType": "D",
                "ServicePoint": "POS-P0001",
                "TransactionReference": "07466f3e-3",
                "CreditAmount": "0.00",
                "DebitAmount": "57869.56",
                "RunningClearedBalance": "-36355.17",
                "RunningBookBalance": "-36355.17",
                "DebitLimit": "0.00",
                "LimitExpiryDate": "2021-02-26 00:00:00"
              }
            ]
          }';
    }

    static public function out_of_range_transactions_error(): string
    {
        return '{
            "MessageReference": "40ca18c6765086089a1",
            "MessageDateTime": "2021-02-26 14:47:00",
            "MessageCode": "-6",
            "MessageDescription": "NUMBER OF TRANSACTIONS IS LESS/GREATER THAN LIMIT ALLOWED"
          }';
    }
}
