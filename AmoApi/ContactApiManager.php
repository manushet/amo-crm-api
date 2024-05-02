<?php

declare(strict_types=1);

namespace AmoApi;

use AmoApi\AmoApi;

class ContactApiManager extends AmoApi
{
    public function getContactById(int $id)
    {
        $url = $this->amoAuth::API_BASE_URL . "/api/v4/contacts/{$id}";

        return $this->amoAuth->send($url, 'GET');
    }

    public function getContactNotes(int $id)
    {
        $url = $this->amoAuth::API_BASE_URL . "/api/v4/contacts/{$id}/notes";

        return $this->amoAuth->send($url, 'GET');
    }

    public function createContactNotes(int $id, mixed $data)
    {
        $url = $this->amoAuth::API_BASE_URL . "/api/v4/contacts/{$id}/notes";

        return $this->amoAuth->send($url, 'POST', $data);
    }
}
