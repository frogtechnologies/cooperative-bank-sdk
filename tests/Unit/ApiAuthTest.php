<?php

use FROG\CooperativeBankSdk\CooperativeBankSdk;
use FROG\CooperativeBankSdk\Curl\SAI_CurlStub;

it('can generate an access token', function () {
    $consumer_key = "zuP_MW9YUs69mpXPZaubHnEo1x8a";
    $consumer_secret = "lWzT7h9UGmsflIP0xzjCQSoV77wa";
    $coop_base_url = "http://developer.co-opbank.co.ke:8280";

    $base_64_auth = base64_encode("$consumer_key:$consumer_secret");

    $auth_headers = ["Authorization: Basic {$base_64_auth}"];
    $auth_data = "grant_type=client_credentials";

    $options = [
        CURLOPT_URL => $coop_base_url . '/token',
        CURLOPT_HTTPHEADER => $auth_headers,
        CURLOPT_SSL_VERIFYPEER  => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $auth_data,
    ];

    $cURL = new SAI_CurlStub();
    $cURL->setResponse(
        '{"access_token":"4fbb7b9e-9c73-3155-ac14-c14fe744d104","scope":"am_application_scope default","token_type":"Bearer","expires_in":3600}',
        $options
    );

    $sdk = new CooperativeBankSdk($cURL);
    $result = $sdk->generate_access_token();

    expect($result)->toBeObject();
    expect($result)->toHaveProperty('access_token');
    expect($result)->toHaveProperty('scope');
    expect($result)->toHaveProperty('token_type');
    expect($result)->toHaveProperty('expires_in');
});
