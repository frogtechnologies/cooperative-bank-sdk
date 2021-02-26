<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

// uses(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/



/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

use FROG\CooperativeBankSdk\CooperativeBankEndpoint;

/**
 * @return array<int, mixed> 
 */
function get_valid_auth_options(): array
{
    $consumer_key = CooperativeBankEndpoint::DEFAULT_CONSUMER_KEY;
    $consumer_secret = CooperativeBankEndpoint::DEFAULT_CONSUMER_SECRET;
    $coop_base_url = CooperativeBankEndpoint::DEFAULT_BASE_URL;

    $base_64_auth = base64_encode("$consumer_key:$consumer_secret");

    $auth_headers = ["Authorization: Basic {$base_64_auth}"];
    $auth_data = "grant_type=client_credentials";

    return [
        CURLOPT_URL => $coop_base_url . '/token',
        CURLOPT_HTTPHEADER => $auth_headers,
        CURLOPT_SSL_VERIFYPEER  => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $auth_data,
    ];
}

/**
 * @param string $access_token
 * @param string $path
 * @param array<string, mixed> $body
 * @return array<int, mixed> 
 */
function get_valid_req_options(
    string $access_token,
    string $path,
    array $body
): array {
    $auth_headers = [
        "Authorization: Bearer {$access_token}",
        "Content-Type: application/json",
    ];

    return [
        CURLOPT_URL => CooperativeBankEndpoint::DEFAULT_BASE_URL . $path,
        CURLOPT_HTTPHEADER => $auth_headers,
        CURLOPT_SSL_VERIFYPEER  => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($body),
    ];
}


function confirm_standard_response(object $result): void
{
    expect($result)->toBeObject();
    expect($result)->toHaveProperty('MessageReference');
    expect($result)->toHaveProperty('MessageDateTime');
    expect($result)->toHaveProperty('MessageCode');
    expect($result)->toHaveProperty('MessageDescription');
}


function confirm_standard_response_v2(object $result): void
{
    expect($result)->toBeObject();
    expect($result)->toHaveProperty('messageReference');
    expect($result)->toHaveProperty('messageDateTime');
    expect($result)->toHaveProperty('messageCode');
    expect($result)->toHaveProperty('messageDescription');
}
