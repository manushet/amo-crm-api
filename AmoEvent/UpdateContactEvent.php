<?php

declare(strict_types=1);

namespace AmoEvent;

use AmoEvent\Event;
use AmoEntity\ContactEntity;
use AmoApi\ContactApiManager;

class UpdateContactEvent extends Event
{
    protected function createAmoApi(): ContactApiManager
    {
        return new ContactApiManager();
    }

    protected function createEntity(): ContactEntity
    {
        return new ContactEntity();
    }

    protected function fillInEntity(): void
    {
        $this->entity->setId((int)$this->entityParams['contacts[update][0][id]']);

        $this->entity->setName($this->entityParams['contacts[update][0][name]']);

        $this->entity->setResponsibleUserId((int)$this->entityParams['contacts[update][0][responsible_user_id]']);

        $this->entity->setCreatedAt((int)$this->entityParams['contacts[update][0][created_at]']);

        $this->entity->setUpdatedAt((int)$this->entityParams['contacts[update][0][updated_at]']);
    }

    protected function addNotes(): void
    {
        $updateEvents = $this->findEventByFilter($this->entity->getUpdatedAt(), "contact", $this->entity->getId());

        $notesDataArray = $this->prepareNotesData($updateEvents);

        $this->amoApi->createContactNotes($this->entity->getId(), $notesDataArray);
    }
}
