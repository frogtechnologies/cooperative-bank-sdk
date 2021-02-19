<?php

namespace FROG\CooperativeBankSdk;

use Dotenv\Dotenv;

interface  CooperativeBankSdk
{
    public function generate_access_token();
    public function check_account_balance(
        string $access_token,
        string $message_reference,
        string $account_number,
    );
}

class CooperativeBankSdkImplementation implements CooperativeBankSdk
{
    function printer($content)
    {
        print "Response\n";
        print_r($content);
        print "Response\n";
    }

    public function generate_access_token()
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $consumer_key = $_ENV['COOP_CONSUMER_KEY'];
        $consumer_secret = $_ENV['COOP_CONSUMER_SECRET'];
        $coop_base_url = $_ENV['COOP_API_BASE_URL'];

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

        $cURL = curl_init();
        curl_setopt_array($cURL, $options);
        $response = curl_exec($cURL);

        if ($response === false)
            $result = curl_error($cURL);
        else
            $result = json_decode($response);

        curl_close($cURL);

        return $result;
    }

    public function check_account_balance(
        string $access_token,
        string $message_reference,
        string $account_number,
    ) {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $coop_base_url = $_ENV['COOP_API_BASE_URL'];

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

        $cURL = curl_init();
        curl_setopt_array($cURL, $options);
        $response = curl_exec($cURL);

        if ($response === false) {
            $result = curl_error($cURL);
        } else {
            $result = json_decode($response);
        }

        curl_close($cURL);


        return $result;
    }
}
