<?php

namespace FROG\CooperativeBankSdk;

/**
 * Specifies the default variables and endpoint to use in the testing environment.
 * 
 */
class CooperativeBankEndpoint
{
    const DEFAULT_BASE_URL = "http://developer.co-opbank.co.ke:8280";
    const DEFAULT_CONSUMER_KEY = "zuP_MW9YUs69mpXPZaubHnEo1x8a";
    const DEFAULT_CONSUMER_SECRET = "lWzT7h9UGmsflIP0xzjCQSoV77wa";

    const ACCESS_TOKEN = "/token";
    const ACCOUNT_BALANCE = "/Enquiry/AccountBalance/1.0.0";

    // NB: As of 26th February 2021, these work in the inverse manner
    const FULL_STATEMENT = "/Enquiry/MiniStatement/Account/1.0.0";
    const MINI_STATEMENT = "/Enquiry/FullStatement/Account/1.0.0";

    const ACCOUNT_TRANSACTIONS = "/Enquiry/AccountTransactions/1.0.0";
    const TRANSACTION_STATUS = "/Enquiry/TransactionStatus/2.0.0";
    const VALIDATE_ACCOUNT = "/Enquiry/Validation/Account/1.0.0";
}
