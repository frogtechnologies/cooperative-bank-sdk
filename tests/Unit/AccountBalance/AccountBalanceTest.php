<?php

use FROG\CooperativeBankSdk\CooperativeBankEndpoint;
use FROG\CooperativeBankSdk\CooperativeBankSdk;
use FROG\CooperativeBankSdk\CoopUtils;
use FROG\CooperativeBankSdk\Tests\Unit\AccountBalance\AccountBalanceResponse;
use FROG\PhpCurlSAI\SAI_CurlStub;

it('can get the account balance of a valid account', function () {

    $cURL = new SAI_CurlStub();
    $sdk = new CooperativeBankSdk($cURL);

    // Setup to successfully retrieve token
    $cURL->setResponse(
        AccountBalanceResponse::auth_success(),
        get_valid_auth_options()
    );
    $token_result = $sdk->generate_access_token();

    $message_reference = CoopUtils::generate_message_reference();
    $request_body = [
        'MessageReference' => $message_reference,
        'AccountNumber' => "36001873000",
    ];

    // Setup to successfully retrieve account balance
    $cURL->setResponse(
        AccountBalanceResponse::success(),
        get_valid_req_options(
            $token_result->access_token,
            CooperativeBankEndpoint::ACCOUNT_BALANCE,
            $request_body,
        )
    );

    $result = $sdk->check_account_balance(
        "36001873000",
        $message_reference
    );

    confirm_standard_response($result);
    expect($result)->toHaveProperty('AccountNumber');
    expect($result)->toHaveProperty('AccountName');
    expect($result)->toHaveProperty('Currency');
    expect($result)->toHaveProperty('ProductName');
    expect($result)->toHaveProperty('ClearedBalance');
    expect($result)->toHaveProperty('BookedBalance');
    expect($result)->toHaveProperty('BlockedBalance');
    expect($result)->toHaveProperty('AvailableBalance');
    expect($result)->toHaveProperty('ArrearsAmount');
    expect($result)->toHaveProperty('BranchName');
    expect($result)->toHaveProperty('BranchSortCode');
    expect($result)->toHaveProperty('AverageBalance');
    expect($result)->toHaveProperty('UnclearedBalance');
    expect($result)->toHaveProperty('ODLimit');
    expect($result)->toHaveProperty('CreditLimit');
});

it('fails to get the account balance if the message reference is longer that the allowed length', function () {
    $cURL = new SAI_CurlStub();
    $sdk = new CooperativeBankSdk($cURL);

    // Setup to successfully retrieve token
    $cURL->setResponse(
        AccountBalanceResponse::auth_success(),
        get_valid_auth_options()
    );
    $token_result = $sdk->generate_access_token();

    $request_body = [
        'MessageReference' => "ac980b8e-afe5-49b8-b348-d0af00e2f556",
        'AccountNumber' => "36001873000",
    ];

    // Setup to fail account balance retrieval
    $cURL->setResponse(
        AccountBalanceResponse::long_message_ref_error(),
        get_valid_req_options(
            $token_result->access_token,
            CooperativeBankEndpoint::ACCOUNT_BALANCE,
            $request_body,
        )
    );

    $result = $sdk->check_account_balance(
        "36001873000",
        "ac980b8e-afe5-49b8-b348-d0af00e2f556",
    );

    confirm_standard_response($result);
});
