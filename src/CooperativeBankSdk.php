<?php

namespace FROG\CooperativeBankSdk;

use Dotenv\Dotenv;
use FROG\PhpCurlSAI\SAI_Curl;
use FROG\PhpCurlSAI\SAI_CurlInterface;

/**
 * Contains the methods to call so that the interaction with the API can happen properly
 */
class  CooperativeBankSdk
{
    protected SAI_CurlInterface $cURL;

    // Hard coded defaults for the testing environment
    protected string $default_base_url = CooperativeBankEndpoint::DEFAULT_BASE_URL;

    /**
     * Sets up the class to use a stubbed cURL implementation class
     * 
     * @param SAI_CurlInterface|void $cURl: A curl implementation of the SAI_CurlInterface
     */
    public function __construct(
        SAI_CurlInterface $cURL = null
    ) {
        if ($cURL == null) $this->cURL = new SAI_Curl();
        else
            $this->cURL = $cURL;
    }

    /**
     * Generates an access token from the Cooperative Bank API
     * 
     * @return object an stdClass of the response from the API 
     * {
     * "access_token": "xxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     * "scope": "am_application_scope default",
     * "token_type": "Bearer",
     * "expires_in": 3600
     * }
     */
    public function generate_access_token(): ?object
    {
        try {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        } catch (\Throwable $th) {
            //throw $th;
        }

        $consumer_key = $_ENV['COOP_CONSUMER_KEY'] ?? CooperativeBankEndpoint::DEFAULT_CONSUMER_KEY;
        $consumer_secret = $_ENV['COOP_CONSUMER_SECRET'] ?? CooperativeBankEndpoint::DEFAULT_CONSUMER_SECRET;
        $coop_base_url = $_ENV['COOP_API_BASE_URL'] ?? $this->default_base_url;

        $base_64_auth = base64_encode("$consumer_key:$consumer_secret");

        $auth_headers = ["Authorization: Basic {$base_64_auth}"];
        $auth_data = "grant_type=client_credentials";

        $options = [
            CURLOPT_URL => $coop_base_url . CooperativeBankEndpoint::ACCESS_TOKEN,
            CURLOPT_HTTPHEADER => $auth_headers,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $auth_data,
        ];

        $ch = $this->cURL->curl_init();
        $this->cURL->curl_setopt_array($ch, $options);
        $response = $this->cURL->curl_exec($ch);

        if ($response === false) {
            $result = $this->cURL->curl_error($ch);
        } else {
            $result = json_decode($response);
        }

        print_r($response);

        $this->cURL->curl_close($ch);

        return $result;
    }

    /**
     * Retrieves the balance of the specified account
     * 
     * @param string $message_reference a unique client generated string to be a reference when a request is sent
     * @param string $account_number an account number from a cooperative bank branch
     * @return object an stdClass of the response from the API
     */
    public function check_account_balance(
        string $account_number,
        ?string $message_reference = null
    ): ?object {
        try {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        } catch (\Throwable $th) {
            //throw $th;
        }

        $_message_reference = $message_reference ?? CoopUtils::generate_message_reference();

        $_coop_base_url = $_ENV['COOP_API_BASE_URL'] ?? $this->default_base_url;
        $_token_result = $this->generate_access_token();

        $auth_headers = [
            "Authorization: Bearer {$_token_result->access_token}",
            "Content-Type: application/json",
        ];

        $request_body = [
            'MessageReference' => $_message_reference,
            'AccountNumber' => $account_number,
        ];

        $options = [
            CURLOPT_URL => $_coop_base_url . CooperativeBankEndpoint::ACCOUNT_BALANCE,
            CURLOPT_HTTPHEADER => $auth_headers,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($request_body),
        ];

        $ch = $this->cURL->curl_init();
        $this->cURL->curl_setopt_array($ch, $options);
        $response = $this->cURL->curl_exec($ch);

        if ($response === false) {
            $result = $this->cURL->curl_error($ch);
        } else {
            $result = json_decode($response);
        }


        $this->cURL->curl_close($ch);


        return $result;
    }

    /**
     * Retrieves the full statement of the specified account
     * 
     * @param string $message_reference a unique client generated string to be a reference when a request is sent
     * @param string $account_number an account number from a cooperative bank branch
     * @param string $start_date the date at which the earliest transaction is to be fetched
     * @param string $end_date the date at which the latest transction is to be fetched
     * @return object an stdClass of the response from the API
     */
    public function get_account_full_statement(

        string $account_number,
        string $start_date,
        string $end_date,
        ?string $message_reference = null
    ): ?object {

        try {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        } catch (\Throwable $th) {
            //throw $th;
        }

        $_coop_base_url = $_ENV['COOP_API_BASE_URL'] ?? $this->default_base_url;
        $_token_result = $this->generate_access_token();
        $_message_reference = $message_reference ?? CoopUtils::generate_message_reference();

        $auth_headers = [
            "Authorization: Bearer {$_token_result->access_token}",
            "Content-Type: application/json",
        ];

        $request_body = [
            'MessageReference' => $_message_reference,
            'AccountNumber' => $account_number,
            "StartDate" => $start_date,
            "EndDate" => $end_date,
        ];

        $options = [
            CURLOPT_URL => $_coop_base_url . CooperativeBankEndpoint::FULL_STATEMENT,
            CURLOPT_HTTPHEADER => $auth_headers,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($request_body),
        ];

        $ch = $this->cURL->curl_init();
        $this->cURL->curl_setopt_array($ch, $options);
        $response = $this->cURL->curl_exec($ch);

        if ($response === false) {
            $result = $this->cURL->curl_error($ch);
        } else {
            $result = json_decode($response);
        }

        $this->cURL->curl_close($ch);

        return $result;
    }

    /**
     * Retrieves the mini statement of the specified account
     * 
     * @param string $message_reference a unique client generated string to be a reference when a request is sent
     * @param string $account_number an account number from a cooperative bank branch
     * @param string $start_date the date at which the earliest transaction is to be fetched
     * @param string $end_date the date at which the latest transaction is to be fetched
     * @return object an stdClass of the response from the API
     */
    public function get_account_mini_statement(

        string $account_number,
        string $start_date,
        string $end_date,
        ?string $message_reference = null
    ): ?object {

        try {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        } catch (\Throwable $th) {
            //throw $th;
        }

        $_coop_base_url = $_ENV['COOP_API_BASE_URL'] ?? $this->default_base_url;
        $_token_result = $this->generate_access_token();
        $_message_reference = $message_reference ?? CoopUtils::generate_message_reference();

        $auth_headers = [
            "Authorization: Bearer {$_token_result->access_token}",
            "Content-Type: application/json",
        ];

        $request_body = [
            'MessageReference' => $_message_reference,
            'AccountNumber' => $account_number,
            "StartDate" => $start_date,
            "EndDate" => $end_date,
        ];

        $options = [
            CURLOPT_URL => $_coop_base_url . CooperativeBankEndpoint::MINI_STATEMENT,
            CURLOPT_HTTPHEADER => $auth_headers,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($request_body),
        ];

        $ch = $this->cURL->curl_init();
        $this->cURL->curl_setopt_array($ch, $options);
        $response = $this->cURL->curl_exec($ch);

        if ($response === false) {
            $result = $this->cURL->curl_error($ch);
        } else {
            $result = json_decode($response);
        }

        $this->cURL->curl_close($ch);

        return $result;
    }

    /**
     * Retrieves the transactions of the specified account
     * 
     * @param string $message_reference a unique client generated string to be a reference when a request is sent
     * @param string $account_number an account number from a cooperative bank branch
     * @param string $no_of_transactions a number of transactions to be retrieved (1 - 500)
     * @return object an stdClass of the response from the API
     */
    public function get_account_transactions(
        string $account_number,
        string $no_of_transactions,
        ?string $message_reference = null
    ): ?object {
        try {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        } catch (\Throwable $th) {
            //throw $th;
        }

        $_coop_base_url = $_ENV['COOP_API_BASE_URL'] ?? $this->default_base_url;
        $_token_result = $this->generate_access_token();
        $_message_reference = $message_reference ?? CoopUtils::generate_message_reference();

        $auth_headers = [
            "Authorization: Bearer {$_token_result->access_token}",
            "Content-Type: application/json",
        ];

        $request_body = [
            'MessageReference' => $_message_reference,
            'AccountNumber' => $account_number,
            "NoOfTransactions" => $no_of_transactions,
        ];

        $options = [
            CURLOPT_URL => $_coop_base_url . CooperativeBankEndpoint::ACCOUNT_TRANSACTIONS,
            CURLOPT_HTTPHEADER => $auth_headers,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($request_body),
        ];

        $ch = $this->cURL->curl_init();
        $this->cURL->curl_setopt_array($ch, $options);
        $response = $this->cURL->curl_exec($ch);

        if ($response === false) {
            $result = $this->cURL->curl_error($ch);
        } else {
            $result = json_decode($response);
        }

        $this->cURL->curl_close($ch);

        return $result;
    }

    /**
     * Retrieves the status of the specified transaction fetched by a message id
     * 
     * @param string $message_reference a unique client generated string to be a reference when a request is sent
     * @return object an stdClass of the response from the API
     */
    public function check_transaction_status(
        string $message_reference
    ): ?object {
        try {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        } catch (\Throwable $th) {
            //throw $th;
        }

        $coop_base_url = $_ENV['COOP_API_BASE_URL'] ?? $this->default_base_url;
        $token_result = $this->generate_access_token();

        $auth_headers = [
            "Authorization: Bearer {$token_result->access_token}",
            "Content-Type: application/json",
        ];

        $request_body = [
            'MessageReference' => $message_reference,
        ];

        $options = [
            CURLOPT_URL => $coop_base_url . CooperativeBankEndpoint::TRANSACTION_STATUS,
            CURLOPT_HTTPHEADER => $auth_headers,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($request_body),
        ];

        $ch = $this->cURL->curl_init();
        $this->cURL->curl_setopt_array($ch, $options);
        $response = $this->cURL->curl_exec($ch);

        if ($response === false) {
            $result = $this->cURL->curl_error($ch);
        } else {
            $result = json_decode($response);
        }

        $this->cURL->curl_close($ch);

        return $result;
    }

    /**
     * Retrieves the validity of the specified account
     * 
     * @param string $message_reference a unique client generated string to be a reference when a request is sent
     * @param string $account_number an account number from a cooperative bank branch
     * @return object an stdClass of the response from the API
     */
    public function validate_account(
        string $account_number,
        ?string $message_reference = null
    ): ?object {
        try {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        } catch (\Throwable $th) {
            //throw $th;
        }

        $_coop_base_url = $_ENV['COOP_API_BASE_URL'] ?? $this->default_base_url;
        $_token_result = $this->generate_access_token();
        $_message_reference = $message_reference ?? CoopUtils::generate_message_reference();

        $auth_headers = [
            "Authorization: Bearer {$_token_result->access_token}",
            "Content-Type: application/json",
        ];

        $request_body = [
            'MessageReference' => $_message_reference,
            'AccountNumber' => $account_number,
        ];

        $options = [
            CURLOPT_URL => $_coop_base_url . CooperativeBankEndpoint::VALIDATE_ACCOUNT,
            CURLOPT_HTTPHEADER => $auth_headers,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($request_body),
        ];

        $ch = $this->cURL->curl_init();
        $this->cURL->curl_setopt_array($ch, $options);
        $response = $this->cURL->curl_exec($ch);

        if ($response === false) {
            $result = $this->cURL->curl_error($ch);
        } else {
            $result = json_decode($response);
        }


        $this->cURL->curl_close($ch);

        return $result;
    }
}
