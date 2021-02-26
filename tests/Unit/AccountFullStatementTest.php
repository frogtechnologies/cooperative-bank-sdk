<?php

use FROG\CooperativeBankSdk\CooperativeBankSdk;
use FROG\PhpCurlSAI\SAI_CurlStub;

it('can get the account mini statement', function () {

    $cURL = new SAI_CurlStub();
    $sdk = new CooperativeBankSdk($cURL);

    // Setup to retrieve token successfully
    $cURL->setResponse(
        '{"access_token":"4fbb7b9e-9c73-3155-ac14-c14fe744d104","scope":"am_application_scope default","token_type":"Bearer","expires_in":3600}',
        get_valid_auth_options()
    );
    $token_result = $sdk->generate_access_token();

    $message_reference = generate_message_reference();
    $request_body = [
        'MessageReference' => $message_reference,
        'AccountNumber' => "36001873000",
        "StartDate" =>  "2009-05-12",
        "EndDate" => "2020-11-12",
    ];

    // Setup to successfully retrieve the account mini statement
    $cURL->setResponse(
        '{
            "MessageReference": "40ca18c6765086089a1",
            "MessageDateTime": "2021-02-26 10:27:16",
            "MessageCode": "0",
            "MessageDescription": "Success",
            "AccountNumber": "36001873000",
            "AccountName": "JOE K. DOE",
            "Transactions": [
              {
                "TransactionID": "50a603a3-2",
                "TransactionDate": "2021-03-07 23:52:28",
                "ValueDate": "2021-03-07 23:52:28",
                "Narration": "Electricity payment",
                "TransactionType": "D",
                "ServicePoint": "POS-P0917",
                "TransactionReference": "8be9b10d-4",
                "CreditAmount": "0.00",
                "DebitAmount": "72869.62",
                "RunningClearedBalance": "22749.45",
                "RunningBookBalance": "22749.45",
                "DebitLimit": "0.00",
                "LimitExpiryDate": "2021-03-07 00:00:00"
              },
              {
                "TransactionID": "96921c98-d",
                "TransactionDate": "2021-03-06 18:04:13",
                "ValueDate": "2021-03-06 18:04:13",
                "Narration": "Cash Deposit",
                "TransactionType": "C",
                "ServicePoint": "AGENT-10001",
                "TransactionReference": "014b9516-6",
                "CreditAmount": "65270.09",
                "DebitAmount": "0.00",
                "RunningClearedBalance": "87479.22",
                "RunningBookBalance": "87479.22",
                "DebitLimit": "0.00",
                "LimitExpiryDate": "2021-03-02 00:00:00"
              },
              {
                "TransactionID": "9934984f-2",
                "TransactionDate": "2021-03-06 10:23:16",
                "ValueDate": "2021-03-06 10:23:16",
                "Narration": "B2C PULL",
                "TransactionType": "D",
                "ServicePoint": "MOBILE",
                "TransactionReference": "2b127db6-8",
                "CreditAmount": "0.00",
                "DebitAmount": "81654.66",
                "RunningClearedBalance": "5824.56",
                "RunningBookBalance": "5824.56",
                "DebitLimit": "0.00",
                "LimitExpiryDate": "2021-03-03 00:00:00"
              },
              {
                "TransactionID": "2bf2abe4-f",
                "TransactionDate": "2021-02-28 00:02:06",
                "ValueDate": "2021-02-28 00:02:06",
                "Narration": "Cash Deposit",
                "TransactionType": "C",
                "ServicePoint": "AGENT-10001",
                "TransactionReference": "9e442620-4",
                "CreditAmount": "89794.51",
                "DebitAmount": "0.00",
                "RunningClearedBalance": "95619.08",
                "RunningBookBalance": "95619.08",
                "DebitLimit": "0.00",
                "LimitExpiryDate": "2021-02-27 00:00:00"
              }
            ]
          }',
        get_valid_req_options(
            $token_result->access_token,
            '/Enquiry/MiniStatement/Account/1.0.0',
            $request_body,
        )
    );

    $result = $sdk->get_account_full_statement(
        $token_result->access_token,
        $message_reference,
        "36001873000",
        "2009-05-12",
        "2020-11-12",
    );

    confirm_standard_response($result);
    expect($result)->toHaveProperty('AccountNumber');
    expect($result)->toHaveProperty('AccountName');
    expect($result)->toHaveProperty('Transactions');
    expect($result->Transactions)->toBeArray();
});
