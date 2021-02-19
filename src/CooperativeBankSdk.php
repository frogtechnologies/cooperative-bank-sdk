<?php

namespace FROG\CooperativeBankSdk;

use Dotenv\Dotenv;

class CooperativeBankSdk
{
    function printer($content)
    {
        print "Response\n";
        print_r($content);
        print "Response\n";
    }

    // Build your next great package.

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
}
