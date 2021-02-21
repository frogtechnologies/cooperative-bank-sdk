<?php

// use FROG\CooperativeBankSdk\CooperativeBankSdk;

// it('can get the account balance of a valid account', function () {

//     $sdk = new CooperativeBankSdk();
//     $token_result = $sdk->generate_access_token();

//     $bytes = random_bytes(19);
//     $message_reference = substr(strtr(base64_encode($bytes), '+/', '-_'), 0, 19);

//     $result = $sdk->check_account_balance(
//         access_token: $token_result->access_token,
//         message_reference: $message_reference,
//         account_number: "36001873000",
//     );

//     expect($result)->toBeObject();
//     expect($result)->toHaveProperty('MessageReference');
//     expect($result)->toHaveProperty('MessageDateTime');
//     expect($result)->toHaveProperty('MessageCode');
//     expect($result)->toHaveProperty('MessageDescription');
//     expect($result)->toHaveProperty('AccountNumber');
//     expect($result)->toHaveProperty('AccountName');
//     expect($result)->toHaveProperty('Currency');
//     expect($result)->toHaveProperty('ProductName');
//     expect($result)->toHaveProperty('ClearedBalance');
//     expect($result)->toHaveProperty('BookedBalance');
//     expect($result)->toHaveProperty('BlockedBalance');
//     expect($result)->toHaveProperty('AvailableBalance');
//     expect($result)->toHaveProperty('ArrearsAmount');
//     expect($result)->toHaveProperty('BranchName');
//     expect($result)->toHaveProperty('BranchSortCode');
//     expect($result)->toHaveProperty('AverageBalance');
//     expect($result)->toHaveProperty('UnclearedBalance');
//     expect($result)->toHaveProperty('ODLimit');
//     expect($result)->toHaveProperty('CreditLimit');
// });

// it('fails to get the account balance if the message reference is longer that the allowed length', function () {
//     $sdk = new CooperativeBankSdk();
//     $token_result = $sdk->generate_access_token();

//     $result = $sdk->check_account_balance(
//         access_token: $token_result->access_token,
//         message_reference: "ac980b8e-afe5-49b8-b348-d0af00e2f556",
//         account_number: "36001873000",
//     );

//     expect($result)->toBeObject();
//     expect($result)->toHaveProperty('MessageReference');
//     expect($result)->toHaveProperty('MessageDateTime');
//     expect($result)->toHaveProperty('MessageCode');
//     expect($result)->toHaveProperty('MessageDescription');
// });
