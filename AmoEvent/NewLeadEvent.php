<?php

declare(strict_types=1);

namespace AmoEvent;

use AmoEvent\Event;
use AmoEntity\LeadEntity;
use AmoApi\LeadApiManager;

class NewLeadEvent extends Event
{
    protected function createAmoApi(): LeadApiManager
    {
        return new LeadApiManager();
    }

    protected function createEntity(): LeadEntity
    {
        return new LeadEntity();
    }

    protected function fillInEntity(): void
    {
        $this->entity->setId((int)$this->entityParams['leads[add][0][id]']);

        $this->entity->setName($this->entityParams['leads[add][0][name]']);

        $this->entity->setResponsibleUserId((int)$this->entityParams['leads[add][0][responsible_user_id]']);

        $this->entity->setCreatedAt((int)$this->entityParams['leads[add][0][created_at]']);

        $this->entity->setUpdatedAt((int)$this->entityParams['leads[add][0][updated_at]']);
    }

    protected function addNotes(): void
    {
        $this->updateResponsibleUserName();

        $notesData = (object)[
            "entity_id" => $this->entity->getId(),
            "note_type" => "common",
            "params" => (object)[
                "text" => "Название сделки: {$this->entity->getName()}. Ответственный: {$this->entity->getResponsibleUserName()}. Дата: {$this->entity->getCreatedAtFormatted()}"
            ]
        ];

        $this->amoApi->createLeadNotes($this->entity->getId(), [$notesData]);
    }

    protected function updateResponsibleUserName(): void
    {
        $responsibleUser = $this->amoApi->getUserById($this->entity->getResponsibleUserId());

        if (!empty($responsibleUser["name"])) {
            $this->entity->setResponsibleUserName($responsibleUser["name"]);
        }
    }
}
