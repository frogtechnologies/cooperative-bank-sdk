<?php

namespace FROG\CooperativeBankSdk;

use Dotenv\Dotenv;
use FROG\PhpCurlSAI\SAI_Curl;
use FROG\PhpCurlSAI\SAI_CurlInterface;

class  CooperativeBankSdk
{
    protected $cURL;

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

        $ch = $this->cURL->curl_init();
        $this->cURL->curl_setopt_array($ch, $options);
        $response = $this->cURL->curl_exec($ch);

        if ($response === false) {
            $result = $this->cURL->curl_error($ch);
            $this->printer($result);
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

        $ch = $this->cURL->curl_init();
        $this->cURL->curl_setopt_array($ch, $options);
        $response = $this->cURL->curl_exec($ch);

        if ($response === false) {
            $result = $this->cURL->curl_error($ch);
            $this->printer($result);
        } else {
            $result = json_decode($response);
            $this->printer($response);
        }


        $this->cURL->curl_close($ch);


        return $result;
    }
}
