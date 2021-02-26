<?php

namespace FROG\CooperativeBankSdk\Tests\Unit\ExchangeRate;

class ExchangeRateResponse
{
    static public function auth_success(): string
    {
        return '{"access_token":"4fbb7b9e-9c73-3155-ac14-c14fe744d104","scope":"am_application_scope default","token_type":"Bearer","expires_in":3600}';
    }

    static public function success(): string
    {
        return '{
            "MessageReference": "40ca18c6765086089a1",
            "MessageDateTime": "2021-02-26 20:31:31",
            "MessageCode": "0",
            "MessageDescription": "Success",
            "FromCurrencyCode": "KES",
            "ToCurrencyCode": "USD",
            "RateType": "SPOT",
            "Rate": "104.35",
            "Tolerance": "6",
            "MultiplyDivide": "D"
          }';
    }

    static public function invalid_currency_error(): string
    {
        return '{
            "MessageReference": "40ca18c6765086089a1",
            "MessageDateTime": "2021-02-26 20:16:40",
            "MessageCode": "-9",
            "MessageDescription": "CURRENCY INVALID/NOT ALLOWED"
          }';
    }

    static public function currency_badly_constructed_error(): string
    {
        return '{
            "MessageReference": "40ca18c6765086089a1",
            "MessageDateTime": "2021-02-26 20:17:40",
            "MessageCode": "-2",
            "MessageDescription": "INVALID/MISSING PARAMETER FromCurrencyCode"
          }';
    }
}
