<?php

declare(strict_types=1);

namespace AmoApi;

use AmoApi\AmoAuth;

abstract class AmoApi
{
    protected AmoAuth $amoAuth;

    public function __construct()
    {
        $this->amoAuth = new AmoAuth();
    }

    public function getUserById(int $id)
    {
        $url = $this->amoAuth::API_BASE_URL . "/api/v4/users/{$id}";

        return $this->amoAuth->send($url, 'GET');
    }

    public function getEvents()
    {
        $url = $this->amoAuth::API_BASE_URL . "/api/v4/events";

        return $this->amoAuth->send($url, 'GET');
    }
}
