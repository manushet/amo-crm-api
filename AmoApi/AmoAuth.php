<?php

declare(strict_types=1);

namespace AmoApi;

use Request\Request;

class AmoAuth
{
    private const REDIRECT_URL = 'https://readu.ru/';

    private const ACCESS_TOKEN_URL = self::API_BASE_URL . "/oauth2/access_token";

    private const TOKEN_FILE = ROOT . "/config/token_info.json";

    private string $accessToken;

    private string $refreshToken;

    private int $expiresIn;

    private int $timestamp;

    public const API_BASE_URL = "https://manu11shet.amocrm.ru";

    public function __construct()
    {
        $this->getTokenInfo();
    }

    private function getTokenInfo()
    {
        $accessTokenInfo = json_decode(file_get_contents(static::TOKEN_FILE), true);

        if (
            isset($accessTokenInfo)
            && isset($accessTokenInfo['accessToken'])
            && isset($accessTokenInfo['refreshToken'])
            && isset($accessTokenInfo['expires'])
            && isset($accessTokenInfo['timestamp'])
        ) {
            $this->accessToken = $accessTokenInfo["accessToken"];
            $this->refreshToken = $accessTokenInfo["refreshToken"];
            $this->expiresIn = $accessTokenInfo["expires"];
            $this->timestamp = $accessTokenInfo["timestamp"];

            if ($this->isAccessTokenExpired()) {
                $this->getAccessTokenByRefreshToken();
            }
        } else {
            $this->getAccessTokenByAuthCode();
        }
    }

    private function saveTokenInfo($tokenInfo): bool
    {
        if (
            isset($tokenInfo)
            && isset($tokenInfo['access_token'])
            && isset($tokenInfo['refresh_token'])
            && isset($tokenInfo['expires_in'])
        ) {
            $data = [
                'accessToken' => $tokenInfo['access_token'],
                'expires' => $tokenInfo['expires_in'],
                'refreshToken' => $tokenInfo['refresh_token'],
                'timestamp' => time(),
            ];

            try {
                file_put_contents(static::TOKEN_FILE, json_encode($data));

                return true;
            } catch (\Throwable $e) {
                throw new \Exception($e->getMessage());
            }
        } else {
            throw new \Exception('Invalid access token info');
        }
    }

    private function isAccessTokenExpired(): bool
    {
        $timestamp = time();

        if ((int)$timestamp >= ((int)$this->expiresIn + (int)$this->timestamp)) {
            return true;
        }

        return false;
    }

    private function getAccessTokenByAuthCode(): void
    {
        $data = [
            'client_id' => CLIENT_ID,
            'client_secret' => SECRET_KEY,
            'grant_type' => 'authorization_code',
            'code' => AUTH_CODE,
            'redirect_uri' => static::REDIRECT_URL,
        ];

        $result = Request::post(static::ACCESS_TOKEN_URL, $data);

        if ($result && $this->saveTokenInfo($result)) {
            $this->getTokenInfo();
        }
    }

    private function getAccessTokenByRefreshToken(): void
    {
        $data = [
            'client_id' => CLIENT_ID,
            'client_secret' => SECRET_KEY,
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->refreshToken,
            'redirect_uri' => static::REDIRECT_URL,
        ];

        $result = Request::post(static::ACCESS_TOKEN_URL, $data);

        if ($result && $this->saveTokenInfo($result)) {
            $this->getTokenInfo();
        }
    }

    public function send(string $url, string $method, mixed $data = []): ?array
    {
        if ($method === "GET") {
            return Request::get($url, $this->accessToken);
        } else {
            return Request::post($url, $data, $method, $this->accessToken);
        }
    }
}
