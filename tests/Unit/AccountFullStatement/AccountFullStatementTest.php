<?php

use FROG\CooperativeBankSdk\CooperativeBankSdk;
use FROG\CooperativeBankSdk\Tests\Unit\AccountFullStatement\AccountFullStatementResponse;
use FROG\PhpCurlSAI\SAI_CurlStub;

it('can get the account full statement', function () {

  $cURL = new SAI_CurlStub();
  $sdk = new CooperativeBankSdk($cURL);

  // Setup to retrieve token successfully
  $cURL->setResponse(
    AccountFullStatementResponse::auth_success(),
    get_valid_auth_options()
  );
  $token_result = $sdk->generate_access_token();

  $message_reference = generate_message_reference();
  $request_body = [
    'MessageReference' => $message_reference,
    'AccountNumber' => "36001873000",
    "StartDate" =>  "2009-05-12",
    "EndDate" => "2020-11-12",
  ];

  // Setup to successfully retrieve the account mini statement
  $cURL->setResponse(
    AccountFullStatementResponse::success(),
    get_valid_req_options(
      $token_result->access_token,
      '/Enquiry/MiniStatement/Account/1.0.0',
      $request_body,
    )
  );

  $result = $sdk->get_account_full_statement(
    $token_result->access_token,
    $message_reference,
    "36001873000",
    "2009-05-12",
    "2020-11-12",
  );

  confirm_standard_response($result);
  expect($result)->toHaveProperty('AccountNumber');
  expect($result)->toHaveProperty('AccountName');
  expect($result)->toHaveProperty('Transactions');
  expect($result->Transactions)->toBeArray();
});
