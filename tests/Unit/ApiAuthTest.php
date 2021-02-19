<?php

use FROG\CooperativeBankSdk\CooperativeBankSdk;

it('can generate an access token', function () {
    $sdk = new CooperativeBankSdk();
    $result = $sdk->generate_access_token();

    expect($result)->toBeObject();
    expect($result)->toHaveProperty('access_token');
    expect($result)->toHaveProperty('scope');
    expect($result)->toHaveProperty('token_type');
    expect($result)->toHaveProperty('expires_in');
});
