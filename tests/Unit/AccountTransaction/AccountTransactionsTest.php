<?php

use FROG\CooperativeBankSdk\CooperativeBankEndpoint;
use FROG\CooperativeBankSdk\CooperativeBankSdk;
use FROG\CooperativeBankSdk\Tests\Unit\AccountTransaction\AccountTransactionsResponse;
use FROG\PhpCurlSAI\SAI_CurlStub;

it('can get the account transactions of a valid account', function () {

    $cURL = new SAI_CurlStub();
    $sdk = new CooperativeBankSdk($cURL);

    // Setup to successfully retrieve token
    $cURL->setResponse(
        AccountTransactionsResponse::auth_success(),
        get_valid_auth_options()
    );
    $token_result = $sdk->generate_access_token();

    $message_reference = generate_message_reference();
    $request_body = [
        'MessageReference' => $message_reference,
        'AccountNumber' => "36001873000",
        'NoOfTransactions' => "3"
    ];

    // Setup to successfully retrieve account balance
    $cURL->setResponse(
        AccountTransactionsResponse::success(),
        get_valid_req_options(
            $token_result->access_token,
            CooperativeBankEndpoint::ACCOUNT_TRANSACTIONS,
            $request_body,
        )
    );

    $result = $sdk->get_account_transactions(
        $message_reference,
        "36001873000",
        "3",
    );

    confirm_standard_response($result);
    expect($result)->toHaveProperty('AccountNumber');
    expect($result)->toHaveProperty('AccountName');
    expect($result)->toHaveProperty('Transactions');
    expect($result->Transactions)->toBeArray();
});


it('fails if the number of transactions is more than the allowed limit', function () {
    $cURL = new SAI_CurlStub();
    $sdk = new CooperativeBankSdk($cURL);

    // Setup to retrieve token successfully
    $cURL->setResponse(
        AccountTransactionsResponse::auth_success(),
        get_valid_auth_options()
    );
    $token_result = $sdk->generate_access_token();

    $message_reference = generate_message_reference();
    $request_body = [
        'MessageReference' => $message_reference,
        'AccountNumber' => "36001873000",
        'NoOfTransactions' => "501"
    ];

    // Setup to successfully retrieve the account mini statement
    $cURL->setResponse(
        AccountTransactionsResponse::out_of_range_transactions_error(),
        get_valid_req_options(
            $token_result->access_token,
            CooperativeBankEndpoint::ACCOUNT_TRANSACTIONS,
            $request_body,
        )
    );

    $result = $sdk->get_account_transactions(
        $message_reference,
        "36001873000",
        "501",
    );
    confirm_standard_response($result);
});

it('fails if the number of transactions is less than the allowed limit', function () {
    $cURL = new SAI_CurlStub();
    $sdk = new CooperativeBankSdk($cURL);

    // Setup to retrieve token successfully
    $cURL->setResponse(
        AccountTransactionsResponse::auth_success(),
        get_valid_auth_options()
    );
    $token_result = $sdk->generate_access_token();

    $message_reference = generate_message_reference();
    $request_body = [
        'MessageReference' => $message_reference,
        'AccountNumber' => "36001873000",
        'NoOfTransactions' => "0"
    ];

    // Setup to successfully retrieve the account mini statement
    $cURL->setResponse(
        AccountTransactionsResponse::out_of_range_transactions_error(),
        get_valid_req_options(
            $token_result->access_token,
            CooperativeBankEndpoint::ACCOUNT_TRANSACTIONS,
            $request_body,
        )
    );

    $result = $sdk->get_account_transactions(
        $message_reference,
        "36001873000",
        "0",
    );
    confirm_standard_response($result);
});
