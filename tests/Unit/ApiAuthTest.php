<?php

use FROG\CooperativeBankSdk\CooperativeBankSdk;
use FROG\PhpCurlSAI\SAI_CurlStub;

it('can generate an access token', function () {

    $cURL = new SAI_CurlStub();
    $cURL->setResponse(
        '{"access_token":"4fbb7b9e-9c73-3155-ac14-c14fe744d104","scope":"am_application_scope default","token_type":"Bearer","expires_in":3600}',
        get_valid_auth_options()
    );

    $sdk = new CooperativeBankSdk($cURL);
    $result = $sdk->generate_access_token();

    expect($result)->toBeObject();
    expect($result)->toHaveProperty('access_token');
    expect($result)->toHaveProperty('scope');
    expect($result)->toHaveProperty('token_type');
    expect($result)->toHaveProperty('expires_in');
});
