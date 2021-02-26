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

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

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

function get_valid_auth_options()
{
    $consumer_key = "zuP_MW9YUs69mpXPZaubHnEo1x8a";
    $consumer_secret = "lWzT7h9UGmsflIP0xzjCQSoV77wa";
    $coop_base_url = "http://developer.co-opbank.co.ke:8280";

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

function get_valid_req_options(
    string $access_token,
    string $path,
    array $body
) {
    $coop_base_url = "http://developer.co-opbank.co.ke:8280";
    $auth_headers = [
        "Authorization: Bearer {$access_token}",
        "Content-Type: application/json",
    ];

    return [
        CURLOPT_URL => $coop_base_url . $path,
        CURLOPT_HTTPHEADER => $auth_headers,
        CURLOPT_SSL_VERIFYPEER  => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($body),
    ];
}

function generate_message_reference()
{
    $bytes = random_bytes(19);
    return substr(strtr(base64_encode($bytes), '+/', '-_'), 0, 19);
}

function confirm_standard_response($result)
{
    expect($result)->toBeObject();
    expect($result)->toHaveProperty('MessageReference');
    expect($result)->toHaveProperty('MessageDateTime');
    expect($result)->toHaveProperty('MessageCode');
    expect($result)->toHaveProperty('MessageDescription');
}
