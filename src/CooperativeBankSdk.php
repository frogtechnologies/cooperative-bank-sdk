<?php

namespace FROG\CooperativeBankSdk;

use Dotenv\Dotenv;
use FROG\PhpCurlSAI\SAI_Curl;
use FROG\PhpCurlSAI\SAI_CurlInterface;

class  CooperativeBankSdk
{
    protected SAI_CurlInterface $cURL;

    // Hard coded defaults for the testing environment
    protected string $consumer_key = CooperativeBankEndpoint::DEFAULT_CONSUMER_KEY;
    protected string $consumer_secret = CooperativeBankEndpoint::DEFAULT_CONSUMER_SECRET;
    protected string $base_url = CooperativeBankEndpoint::DEFAULT_BASE_URL;

    public function __construct(
        SAI_CurlInterface $cURL = null
    ) {
        if ($cURL == null) $this->cURL = new SAI_Curl();
        else
            $this->cURL = $cURL;
    }

    public function generate_access_token(): ?object
    {
        try {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        } catch (\Throwable $th) {
            //throw $th;
        }

        $consumer_key = $_ENV['COOP_CONSUMER_KEY'] ?? $this->consumer_key;
        $consumer_secret = $_ENV['COOP_CONSUMER_SECRET'] ?? $this->consumer_secret;
        $coop_base_url = $_ENV['COOP_API_BASE_URL'] ?? $this->base_url;

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

    public function check_account_balance(
        string $message_reference,
        string $account_number
    ): ?object {
        try {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        } catch (\Throwable $th) {
            //throw $th;
        }

        $coop_base_url = $_ENV['COOP_API_BASE_URL'] ?? $this->base_url;
        $token_result = $this->generate_access_token();

        $auth_headers = [
            "Authorization: Bearer {$token_result->access_token}",
            "Content-Type: application/json",
        ];

        $request_body = [
            'MessageReference' => $message_reference,
            'AccountNumber' => $account_number,
        ];

        $options = [
            CURLOPT_URL => $coop_base_url . CooperativeBankEndpoint::ACCOUNT_BALANCE,
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

    public function get_account_full_statement(
        string $message_reference,
        string $account_number,
        string $start_date,
        string $end_date
    ): ?object {

        try {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        } catch (\Throwable $th) {
            //throw $th;
        }

        $coop_base_url = $_ENV['COOP_API_BASE_URL'] ?? $this->base_url;
        $token_result = $this->generate_access_token();

        $auth_headers = [
            "Authorization: Bearer {$token_result->access_token}",
            "Content-Type: application/json",
        ];

        $request_body = [
            'MessageReference' => $message_reference,
            'AccountNumber' => $account_number,
            "StartDate" => $start_date,
            "EndDate" => $end_date,
        ];

        $options = [
            CURLOPT_URL => $coop_base_url . CooperativeBankEndpoint::FULL_STATEMENT,
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

    public function get_account_mini_statement(
        string $message_reference,
        string $account_number,
        string $start_date,
        string $end_date
    ): ?object {

        try {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        } catch (\Throwable $th) {
            //throw $th;
        }

        $coop_base_url = $_ENV['COOP_API_BASE_URL'] ?? $this->base_url;
        $token_result = $this->generate_access_token();

        $auth_headers = [
            "Authorization: Bearer {$token_result->access_token}",
            "Content-Type: application/json",
        ];

        $request_body = [
            'MessageReference' => $message_reference,
            'AccountNumber' => $account_number,
            "StartDate" => $start_date,
            "EndDate" => $end_date,
        ];

        $options = [
            CURLOPT_URL => $coop_base_url . CooperativeBankEndpoint::MINI_STATEMENT,
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

    public function get_account_transactions(
        string $message_reference,
        string $account_number,
        string $no_of_transactions
    ): ?object {
        try {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        } catch (\Throwable $th) {
            //throw $th;
        }

        $coop_base_url = $_ENV['COOP_API_BASE_URL'] ?? $this->base_url;
        $token_result = $this->generate_access_token();

        $auth_headers = [
            "Authorization: Bearer {$token_result->access_token}",
            "Content-Type: application/json",
        ];

        $request_body = [
            'MessageReference' => $message_reference,
            'AccountNumber' => $account_number,
            "NoOfTransactions" => $no_of_transactions,
        ];

        $options = [
            CURLOPT_URL => $coop_base_url . CooperativeBankEndpoint::ACCOUNT_TRANSACTIONS,
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

    public function check_transaction_status(
        string $message_reference
    ): ?object {
        try {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        } catch (\Throwable $th) {
            //throw $th;
        }

        $coop_base_url = $_ENV['COOP_API_BASE_URL'] ?? $this->base_url;
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

    public function validate_account(
        string $message_reference,
        string $account_number
    ): ?object {
        try {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        } catch (\Throwable $th) {
            //throw $th;
        }

        $coop_base_url = $_ENV['COOP_API_BASE_URL'] ?? $this->base_url;
        $token_result = $this->generate_access_token();

        $auth_headers = [
            "Authorization: Bearer {$token_result->access_token}",
            "Content-Type: application/json",
        ];

        $request_body = [
            'MessageReference' => $message_reference,
            'AccountNumber' => $account_number,
        ];

        $options = [
            CURLOPT_URL => $coop_base_url . CooperativeBankEndpoint::VALIDATE_ACCOUNT,
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
