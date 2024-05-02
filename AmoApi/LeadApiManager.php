<?php

declare(strict_types=1);

namespace AmoApi;

use AmoApi\AmoApi;

class LeadApiManager extends AmoApi
{
    public function getLeadById(int $id)
    {
        $url = $this->amoAuth::API_BASE_URL . "/api/v4/leads/{$id}";

        return $this->amoAuth->send($url, 'GET');
    }

    public function getLeadNotes(int $id)
    {
        $url = $this->amoAuth::API_BASE_URL . "/api/v4/leads/{$id}/notes";

        return $this->amoAuth->send($url, 'GET');
    }

    public function createLeadNotes(int $id, mixed $data)
    {
        $url = $this->amoAuth::API_BASE_URL . "/api/v4/leads/{$id}/notes";

        return $this->amoAuth->send($url, 'POST', $data);
    }
}
