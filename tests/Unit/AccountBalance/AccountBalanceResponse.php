<?php

namespace FROG\CooperativeBankSdk\Tests\Unit\AccountBalance;

class AccountBalanceResponse
{
    static public function auth_success() : string
    {
        return '{"access_token":"4fbb7b9e-9c73-3155-ac14-c14fe744d104","scope":"am_application_scope default","token_type":"Bearer","expires_in":3600}';
    }

    static public function success() : string
    {
        return '{
            "MessageReference": "40ca18c6765086089a1",
            "MessageDateTime": "2021-02-26 11:28:38",
            "MessageCode": "0",
            "MessageDescription": "Success",
            "AccountNumber": "36001873000",
            "AccountName": "JOE K. DOE",
            "Currency": "USD",
            "ProductName": "CURRENT ACCOUNT",
            "ClearedBalance": "13706.07",
            "BookedBalance": "75391.31",
            "BlockedBalance": "27066.64",
            "AvailableBalance": "21962.96",
            "ArrearsAmount": "12645.56",
            "BranchName": "GIGIRI MALL",
            "BranchSortCode": "11151",
            "AverageBalance": "27339.95",
            "UnclearedBalance": "26658.48",
            "ODLimit": "17614.28",
            "CreditLimit": "23181.53"
          }';
    }

    static public function long_message_ref_error() : string
    {
        return '{
            "MessageReference": "ac980b8e-afe5-49b8-b348-d0af00e2f556",
            "MessageDateTime": "2021-02-26 11:30:01",
            "MessageCode": "-11",
            "MessageDescription": "MESSAGE REFERENCE LONGER THAN ALLOWED LENGTH"
          }';
    }
}
