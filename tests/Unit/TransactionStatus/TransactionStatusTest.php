<?php

use FROG\CooperativeBankSdk\CooperativeBankEndpoint;
use FROG\CooperativeBankSdk\CooperativeBankSdk;
use FROG\CooperativeBankSdk\Tests\Unit\TransactionStatus\TransactionStatusResponse;
use FROG\PhpCurlSAI\SAI_CurlStub;

it('can get the status of a valid reference message transaction', function () {

    $cURL = new SAI_CurlStub();
    $sdk = new CooperativeBankSdk($cURL);

    // Setup to successfully retrieve token
    $cURL->setResponse(
        TransactionStatusResponse::auth_success(),
        get_valid_auth_options()
    );
    $token_result = $sdk->generate_access_token();

    $request_body = [
        'MessageReference' => "40ca18c6765086089a1",
    ];

    // Setup to successfully retrieve account balance
    $cURL->setResponse(
        TransactionStatusResponse::success(),
        get_valid_req_options(
            $token_result->access_token,
            CooperativeBankEndpoint::TRANSACTION_STATUS,
            $request_body,
        )
    );

    $result = $sdk->check_transaction_status(
        "40ca18c6765086089a1",
    );

    confirm_standard_response_v2($result);
    expect($result)->toHaveProperty('source');
    expect($result)->toHaveProperty('destination');
    expect($result->source)->toBeObject();
    expect($result->destination)->toBeObject();
});


it('fails to get the status of a non existent reference message', function () {

    $cURL = new SAI_CurlStub();
    $sdk = new CooperativeBankSdk($cURL);

    // Setup to successfully retrieve token
    $cURL->setResponse(
        TransactionStatusResponse::auth_success(),
        get_valid_auth_options()
    );
    $token_result = $sdk->generate_access_token();

    $request_body = [
        'MessageReference' => "BTVd6xr7vEX97hWgNqM",
    ];

    // Setup to successfully retrieve account balance
    $cURL->setResponse(
        TransactionStatusResponse::no_message_reference_error(),
        get_valid_req_options(
            $token_result->access_token,
            CooperativeBankEndpoint::TRANSACTION_STATUS,
            $request_body,
        )
    );

    $result = $sdk->check_transaction_status(
        "BTVd6xr7vEX97hWgNqM",
    );

    confirm_standard_response($result);
    expect($result)->toHaveProperty('Source');
    expect($result)->toHaveProperty('Destinations');
    expect($result->Source)->toBeObject();
    expect($result->Destinations)->toBeNull();
});
