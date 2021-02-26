<?php

use FROG\CooperativeBankSdk\CooperativeBankEndpoint;
use FROG\CooperativeBankSdk\CooperativeBankSdk;
use FROG\CooperativeBankSdk\CoopUtils;
use FROG\CooperativeBankSdk\Tests\Unit\ExchangeRate\ExchangeRateResponse;
use FROG\PhpCurlSAI\SAI_CurlStub;

it('gets the exchange rate when correct parameters are provided', function () {

    $cURL = new SAI_CurlStub();
    $sdk = new CooperativeBankSdk($cURL);

    // Setup to successfully retrieve token
    $cURL->setResponse(
        ExchangeRateResponse::auth_success(),
        get_valid_auth_options()
    );
    $token_result = $sdk->generate_access_token();

    $message_reference = CoopUtils::generate_message_reference();
    $request_body = [
        'MessageReference' => $message_reference,
        'FromCurrencyCode' => "KES",
        'ToCurrencyCode' => "USD",
    ];

    // Setup to successfully retrieve account balance
    $cURL->setResponse(
        ExchangeRateResponse::success(),
        get_valid_req_options(
            $token_result->access_token,
            CooperativeBankEndpoint::EXCHANGE_RATE,
            $request_body,
        )
    );

    $result = $sdk->exchange_rate(
        "KES",
        "USD",
        $message_reference,
    );

    confirm_standard_response($result);
    expect($result)->toHaveProperty("FromCurrencyCode");
    expect($result)->toHaveProperty("ToCurrencyCode");
    expect($result)->toHaveProperty("RateType");
    expect($result)->toHaveProperty("Rate");
    expect($result)->toHaveProperty("Tolerance");
    expect($result)->toHaveProperty("MultiplyDivide");
});


it('fails to get the exchange rate if currency is badly constructed', function () {
    $cURL = new SAI_CurlStub();
    $sdk = new CooperativeBankSdk($cURL);

    // Setup to successfully retrieve token
    $cURL->setResponse(
        ExchangeRateResponse::auth_success(),
        get_valid_auth_options()
    );
    $token_result = $sdk->generate_access_token();
    $message_reference = CoopUtils::generate_message_reference();

    $request_body = [
        'MessageReference' => $message_reference,
        'FromCurrencyCode' => "UsD",
        'ToCurrencyCode' => "KES",
    ];

    // Setup to fail account balance retrieval
    $cURL->setResponse(
        ExchangeRateResponse::currency_badly_constructed_error(),
        get_valid_req_options(
            $token_result->access_token,
            CooperativeBankEndpoint::EXCHANGE_RATE,
            $request_body,
        )
    );

    $result = $sdk->exchange_rate(
        "UsD",
        "KES",
        $message_reference,
    );

    confirm_standard_response($result);
});



it('fails to get the exchange rate if currency is invalid', function () {
    $cURL = new SAI_CurlStub();
    $sdk = new CooperativeBankSdk($cURL);

    // Setup to successfully retrieve token
    $cURL->setResponse(
        ExchangeRateResponse::auth_success(),
        get_valid_auth_options()
    );
    $token_result = $sdk->generate_access_token();
    $message_reference = CoopUtils::generate_message_reference();

    $request_body = [
        'MessageReference' => $message_reference,
        'FromCurrencyCode' => "KES",
        'ToCurrencyCode' => "KES",
    ];

    // Setup to fail account balance retrieval
    $cURL->setResponse(
        ExchangeRateResponse::currency_badly_constructed_error(),
        get_valid_req_options(
            $token_result->access_token,
            CooperativeBankEndpoint::EXCHANGE_RATE,
            $request_body,
        )
    );

    $result = $sdk->exchange_rate(
        "KES",
        "KES",
        $message_reference,
    );

    confirm_standard_response($result);
});
