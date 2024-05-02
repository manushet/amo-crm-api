<?php

declare(strict_types=1);

namespace Request;

class Request
{
    public static function get(string $url, string $accessToken): ?array
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer {$accessToken}"
            ),
        ));

        $output = curl_exec($curl);

        curl_close($curl);

        return static::getResponse($output);
    }

    public static function post(string $url, mixed $data, string $method = 'POST', string $accessToken = ""): ?array
    {
        $curl = curl_init();

        $header = array('Content-Type: application/json');

        if (!empty($accessToken)) {
            $header[] = "Authorization: Bearer {$accessToken}";
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $header,
        ));

        $output = curl_exec($curl);

        curl_close($curl);

        return static::getResponse($output);
    }

    private static function getResponse($output): ?array
    {
        if ($output) {

            $response = json_decode($output, true);

            if (isset($response["status"])) {
                throw new \Exception($output);
            }

            return $response;
        }

        return null;
    }
}
