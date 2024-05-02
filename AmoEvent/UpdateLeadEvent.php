<?php

declare(strict_types=1);

namespace AmoEvent;

use AmoEvent\Event;
use AmoEntity\LeadEntity;
use AmoApi\LeadApiManager;

class UpdateLeadEvent extends Event
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
        $this->entity->setId((int)$this->entityParams['leads[update][0][id]']);

        $this->entity->setName($this->entityParams['leads[update][0][name]']);

        $this->entity->setResponsibleUserId((int)$this->entityParams['leads[update][0][responsible_user_id]']);

        $this->entity->setCreatedAt((int)$this->entityParams['leads[update][0][created_at]']);

        $this->entity->setUpdatedAt((int)$this->entityParams['leads[update][0][updated_at]']);
    }

    protected function addNotes(): void
    {
        $updateEvents = $this->findEventByFilter($this->entity->getUpdatedAt(), "lead", $this->entity->getId());

        $notesDataArray = $this->prepareNotesData($updateEvents);

        $this->amoApi->createLeadNotes($this->entity->getId(), $notesDataArray);
    }
}
