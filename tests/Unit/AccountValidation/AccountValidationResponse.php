<?php

namespace FROG\CooperativeBankSdk\Tests\Unit\AccountValidation;

class AccountValidationResponse
{
    static public function auth_success(): string
    {
        return '{"access_token":"4fbb7b9e-9c73-3155-ac14-c14fe744d104","scope":"am_application_scope default","token_type":"Bearer","expires_in":3600}';
    }

    static public function success(): string
    {
        return '{
            "MessageReference": "BTVd6xr7vEX97hWgNqM",
            "MessageDateTime": "2021-02-26 16:23:30",
            "MessageCode": "0",
            "MessageDescription": "VALID ACCOUNT NUMBER"
          }';
    }

    static public function invalid_account_error(): string
    {
        return '{
            "MessageReference": "BTVd6xr7vEX97hWgNqM",
            "MessageDateTime": "2021-02-26 16:24:31",
            "MessageCode": "-1",
            "MessageDescription": "INVALID ACCOUNT NUMBER"
          }';
    }
}
