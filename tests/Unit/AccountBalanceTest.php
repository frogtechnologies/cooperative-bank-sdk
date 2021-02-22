<?php

use FROG\CooperativeBankSdk\CooperativeBankSdk;
use FROG\PhpCurlSAI\SAI_CurlStub;

it('can get the account balance of a valid account', function () {

    $cURL = new SAI_CurlStub();
    $sdk = new CooperativeBankSdk($cURL);

    // Setup to successfully retrieve token
    $cURL->setResponse(
        '{"access_token":"4fbb7b9e-9c73-3155-ac14-c14fe744d104","scope":"am_application_scope default","token_type":"Bearer","expires_in":3600}',
        get_valid_auth_options()
    );
    $token_result = $sdk->generate_access_token();

    $bytes = random_bytes(19);
    $message_reference = substr(strtr(base64_encode($bytes), '+/', '-_'), 0, 19);

    $request_body = [
        'MessageReference' => $message_reference,
        'AccountNumber' => "36001873000",
    ];
    // Setup to successfully retrieve account balance
    $cURL->setResponse(
        '{"MessageReference":"EMj0thurFs9Lb0yJ0ve","MessageDateTime":"2021-02-21 22:16:26","MessageCode":"0","MessageDescription":"Success","AccountNumber":"36001873000","AccountName":"JOE K. DOE","Currency":"USD","ProductName":"CURRENT ACCOUNT","ClearedBalance":"13706.07","BookedBalance":"75391.31","BlockedBalance":"27066.64","AvailableBalance":"21962.96","ArrearsAmount":"12645.56","BranchName":"GIGIRI MALL","BranchSortCode":"11151","AverageBalance":"27339.95","UnclearedBalance":"26658.48","ODLimit":"17614.28","CreditLimit":"23181.53"}',
        get_valid_req_options(
            $token_result->access_token,
            '/Enquiry/AccountBalance/1.0.0',
            $request_body,
        )
    );

    $result = $sdk->check_account_balance(
        $token_result->access_token,
        $message_reference,
        "36001873000",
    );

    expect($result)->toBeObject();
    expect($result)->toHaveProperty('MessageReference');
    expect($result)->toHaveProperty('MessageDateTime');
    expect($result)->toHaveProperty('MessageCode');
    expect($result)->toHaveProperty('MessageDescription');
    expect($result)->toHaveProperty('AccountNumber');
    expect($result)->toHaveProperty('AccountName');
    expect($result)->toHaveProperty('Currency');
    expect($result)->toHaveProperty('ProductName');
    expect($result)->toHaveProperty('ClearedBalance');
    expect($result)->toHaveProperty('BookedBalance');
    expect($result)->toHaveProperty('BlockedBalance');
    expect($result)->toHaveProperty('AvailableBalance');
    expect($result)->toHaveProperty('ArrearsAmount');
    expect($result)->toHaveProperty('BranchName');
    expect($result)->toHaveProperty('BranchSortCode');
    expect($result)->toHaveProperty('AverageBalance');
    expect($result)->toHaveProperty('UnclearedBalance');
    expect($result)->toHaveProperty('ODLimit');
    expect($result)->toHaveProperty('CreditLimit');
});

it('fails to get the account balance if the message reference is longer that the allowed length', function () {
    $cURL = new SAI_CurlStub();
    $sdk = new CooperativeBankSdk($cURL);

    // Setup to successfully retrieve token
    $cURL->setResponse(
        '{"access_token":"4fbb7b9e-9c73-3155-ac14-c14fe744d104","scope":"am_application_scope default","token_type":"Bearer","expires_in":3600}',
        get_valid_auth_options()
    );
    $token_result = $sdk->generate_access_token();

    $request_body = [
        'MessageReference' => "ac980b8e-afe5-49b8-b348-d0af00e2f556",
        'AccountNumber' => "36001873000",
    ];

    // Setup to fail account balance retrieval
    $cURL->setResponse(
        '{"MessageReference":"ac980b8e-afe5-49b8-b348-d0af00e2f556","MessageDateTime":"2021-02-21 22:20:32","MessageCode":"-11","MessageDescription":"MESSAGE REFERENCE LONGER THAN ALLOWED LENGTH"}',
        get_valid_req_options(
            $token_result->access_token,
            '/Enquiry/AccountBalance/1.0.0',
            $request_body,
        )
    );

    $result = $sdk->check_account_balance(
        $token_result->access_token,
        "ac980b8e-afe5-49b8-b348-d0af00e2f556",
        "36001873000",
    );

    expect($result)->toBeObject();
    expect($result)->toHaveProperty('MessageReference');
    expect($result)->toHaveProperty('MessageDateTime');
    expect($result)->toHaveProperty('MessageCode');
    expect($result)->toHaveProperty('MessageDescription');
});
