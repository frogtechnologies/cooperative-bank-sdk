<?php

use FROG\CooperativeBankSdk\CooperativeBankSdk;
use FROG\CooperativeBankSdk\Tests\Unit\AccountMiniStatement\AccountMiniStatementResponse;
use FROG\PhpCurlSAI\SAI_CurlStub;

it('can get the account mini statement', function () {

  $cURL = new SAI_CurlStub();
  $sdk = new CooperativeBankSdk($cURL);

  // Setup to retrieve token successfully
  $cURL->setResponse(
    AccountMiniStatementResponse::auth_success(),
    get_valid_auth_options()
  );
  $token_result = $sdk->generate_access_token();

  $message_reference = generate_message_reference();
  $request_body = [
    'MessageReference' => $message_reference,
    'AccountNumber' => "36001873000",
    "StartDate" =>  "2020-11-12",
    "EndDate" => "2021-02-26",
  ];

  // Setup to successfully retrieve the account mini statement
  $cURL->setResponse(
    AccountMiniStatementResponse::success(),
    get_valid_req_options(
      $token_result->access_token,
      '/Enquiry/FullStatement/Account/1.0.0',
      $request_body,
    )
  );

  $result = $sdk->get_account_mini_statement(
    $token_result->access_token,
    $message_reference,
    "36001873000",
    "2020-11-12",
    "2021-02-26",
  );

  confirm_standard_response($result);
  expect($result)->toHaveProperty('AccountNumber');
  expect($result)->toHaveProperty('AccountName');
  expect($result)->toHaveProperty('Transactions');
  expect($result->Transactions)->toBeArray();
});


it('fails if the date range is more than 6 months from the current date', function () {
  $cURL = new SAI_CurlStub();
  $sdk = new CooperativeBankSdk($cURL);

  // Setup to retrieve token successfully
  $cURL->setResponse(
    AccountMiniStatementResponse::auth_success(),
    get_valid_auth_options()
  );
  $token_result = $sdk->generate_access_token();

  $message_reference = generate_message_reference();
  $request_body = [
    'MessageReference' => $message_reference,
    'AccountNumber' => "36001873000",
    "StartDate" =>  "2020-08-12",
    "EndDate" => "2020-11-12",
  ];

  // Setup to successfully retrieve the account mini statement
  $cURL->setResponse(
    AccountMiniStatementResponse::out_of_range_date_error(),
    get_valid_req_options(
      $token_result->access_token,
      '/Enquiry/FullStatement/Account/1.0.0',
      $request_body,
    )
  );

  $result = $sdk->get_account_mini_statement(
    $token_result->access_token,
    $message_reference,
    "36001873000",
    "2020-08-12",
    "2020-11-12",
  );

  confirm_standard_response($result);
});
