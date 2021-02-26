<?php

use FROG\CooperativeBankSdk\CooperativeBankEndpoint;
use FROG\CooperativeBankSdk\CooperativeBankSdk;
use FROG\CooperativeBankSdk\CoopUtils;
use FROG\CooperativeBankSdk\Tests\Unit\AccountValidation\AccountValidationResponse;
use FROG\PhpCurlSAI\SAI_CurlStub;

it('validates a valid account', function () {
    $cURL = new SAI_CurlStub();
    $sdk = new CooperativeBankSdk($cURL);

    // Setup to successfully retrieve token
    $cURL->setResponse(
        AccountValidationResponse::auth_success(),
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
        AccountValidationResponse::success(),
        get_valid_req_options(
            $token_result->access_token,
            CooperativeBankEndpoint::VALIDATE_ACCOUNT,
            $request_body,
        )
    );

    $result = $sdk->validate_account(
        "36001873000",
        $message_reference,
    );

    confirm_standard_response($result);
    expect($result)->toMatchObject([
        "MessageDescription" => 'VALID ACCOUNT NUMBER',
        "MessageCode" => "0"
    ]);
});


it('fails to validate an invalid account', function () {
    $cURL = new SAI_CurlStub();
    $sdk = new CooperativeBankSdk($cURL);

    // Setup to successfully retrieve token
    $cURL->setResponse(
        AccountValidationResponse::auth_success(),
        get_valid_auth_options()
    );
    $token_result = $sdk->generate_access_token();

    $message_reference = CoopUtils::generate_message_reference();
    $request_body = [
        'MessageReference' => $message_reference,
        'AccountNumber' => "3600187300",
    ];

    // Setup to successfully retrieve account balance
    $cURL->setResponse(
        AccountValidationResponse::invalid_account_error(),
        get_valid_req_options(
            $token_result->access_token,
            CooperativeBankEndpoint::VALIDATE_ACCOUNT,
            $request_body,
        )
    );

    $result = $sdk->validate_account(
        "3600187300",
        $message_reference,
    );

    confirm_standard_response($result);
    expect($result)->toMatchObject([
        "MessageDescription" => 'INVALID ACCOUNT NUMBER',
        "MessageCode" => "-1"
    ]);
});
