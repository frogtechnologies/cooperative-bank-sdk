<?php

namespace FROG\CooperativeBankSdk;

use Dotenv\Dotenv;
use FROG\PhpCurlSAI\SAI_Curl;
use FROG\PhpCurlSAI\SAI_CurlInterface;

class  CooperativeBankSdk
{
    protected $cURL;

    // Hard coded defaults for the testing environment
    protected $consumer_key = "zuP_MW9YUs69mpXPZaubHnEo1x8a";
    protected $consumer_secret = "lWzT7h9UGmsflIP0xzjCQSoV77wa";
    protected $base_url = "http://developer.co-opbank.co.ke:8280";

    public function __construct(
        SAI_CurlInterface $cURL = null
    ) {
        if ($cURL == null) $this->cURL = new SAI_Curl();
        else
            $this->cURL = $cURL;
    }

    function printer($content)
    {
        print "Response\n";
        print_r($content);
        print "Response\n";
    }

    public function generate_access_token()
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
            CURLOPT_URL => $coop_base_url . '/token',
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
            $this->printer($response);
        }

        $this->cURL->curl_close($ch);

        return $result;
    }

    public function check_account_balance(
        string $access_token,
        string $message_reference,
        string $account_number
    ) {
        try {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        } catch (\Throwable $th) {
            //throw $th;
        }

        $coop_base_url = $_ENV['COOP_API_BASE_URL'] ?? $this->base_url;

        $auth_headers = [
            "Authorization: Bearer {$access_token}",
            "Content-Type: application/json",
        ];

        $request_body = [
            'MessageReference' => $message_reference,
            'AccountNumber' => $account_number,
        ];

        $options = [
            CURLOPT_URL => $coop_base_url . '/Enquiry/AccountBalance/1.0.0',
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
        string $access_token,
        string $message_reference,
        string $account_number,
        string $start_date,
        string $end_date,
    ) {

        try {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        } catch (\Throwable $th) {
            //throw $th;
        }

        $coop_base_url = $_ENV['COOP_API_BASE_URL'] ?? $this->base_url;

        $auth_headers = [
            "Authorization: Bearer {$access_token}",
            "Content-Type: application/json",
        ];

        $request_body = [
            'MessageReference' => $message_reference,
            'AccountNumber' => $account_number,
            "StartDate" => $start_date,
            "EndDate" => $end_date,
        ];

        $options = [
            CURLOPT_URL => $coop_base_url . '/Enquiry/MiniStatement/Account/1.0.0',
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
        string $access_token,
        string $message_reference,
        string $account_number,
        string $start_date,
        string $end_date,
    ) {

        try {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        } catch (\Throwable $th) {
            //throw $th;
        }

        $coop_base_url = $_ENV['COOP_API_BASE_URL'] ?? $this->base_url;

        $auth_headers = [
            "Authorization: Bearer {$access_token}",
            "Content-Type: application/json",
        ];

        $request_body = [
            'MessageReference' => $message_reference,
            'AccountNumber' => $account_number,
            "StartDate" => $start_date,
            "EndDate" => $end_date,
        ];

        $options = [
            CURLOPT_URL => $coop_base_url . '/Enquiry/FullStatement/Account/1.0.0',
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
