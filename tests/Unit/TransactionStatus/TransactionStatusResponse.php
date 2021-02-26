<?php

namespace FROG\CooperativeBankSdk\Tests\Unit\TransactionStatus;


class TransactionStatusResponse
{
    static public function auth_success(): string
    {
        return '{"access_token":"4fbb7b9e-9c73-3155-ac14-c14fe744d104","scope":"am_application_scope default","token_type":"Bearer","expires_in":3600}';
    }

    static public function success()
    {
        return '{
            "messageReference": "40ca18c6765086089a1",
            "messageDateTime": "2021-02-26 15:23:32",
            "messageCode": "0",
            "messageDescription": "Full Success",
            "source": {
              "accountNumber": "36001873000",
              "amount": "777",
              "transactionCurrency": "KES",
              "narration": "Supplier Payment",
              "responseCode": "0",
              "responseDescription": "Success"
            },
            "destination": {
              "referenceNumber": "40ca18c6765086089a1_1",
              "accountNumber": "54321987654321",
              "amount": "777",
              "transactionCurrency": "KES",
              "narration": "Electricity Payment",
              "transactionID": "cfea0f9d-c",
              "responseCode": "0",
              "responseDescription": "Success"
            }
          }';
    }

    public function no_message_reference_error()
    {
        return '{
            "MessageReference": "BTVd6xr7vEX97hWgNqM",
            "MessageDateTime": "2021-02-26 15:35:03",
            "MessageCode": -13,
            "MessageDescription": "MESSAGE REFERENCE DOES NOT EXIST",
            "Source": {
              "AccountNumber": null,
              "Amount": null,
              "TransactionCurrency": null,
              "Narration": null,
              "ResponseCode": null,
              "ResponseDescription": null
            },
            "Destinations": null
          }';
    }
}
