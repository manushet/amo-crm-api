<?php

declare(strict_types=1);

namespace AmoEvent;

use AmoEntity\Entity;

use AmoApi\AmoApi;

abstract class Event
{
    protected AmoApi $amoApi;

    protected Entity $entity;

    protected array $entityParams = [];

    abstract protected function createAmoApi(): AmoApi;

    abstract protected function createEntity(): Entity;

    abstract protected function fillInEntity(): void;

    abstract protected function addNotes(): void;

    public function handle(string $entityBody): void
    {
        $this->amoApi = static::createAmoApi();

        $this->entity = static::createEntity();

        $this->parseEntityBody($entityBody);

        $this->fillInEntity();

        $this->addNotes();
    }

    protected function findEventByFilter(int $timestamp, string $entity_type, int $entity_id): array
    {
        $events = $this->amoApi->getEvents();

        $filteredEvents = [];
        if (!empty($events) && count($events["_embedded"]["events"])) {
            foreach ($events["_embedded"]["events"] as $key => $event) {
                if (((int)$event["created_at"] === (int)$timestamp)
                    && (count($event["value_after"]) > 0)
                    && ($event["entity_type"] === $entity_type)
                    && ($event["entity_id"] === $entity_id)
                ) {
                    $filteredEvents[] = $event;
                }
            }
        }

        return $filteredEvents;
    }

    protected function parseEntityBody(string $entityBody)
    {
        $response = explode("&", html_entity_decode(urldecode($entityBody)));

        foreach ($response as $parameter) {
            $parameterPair = explode("=", $parameter);
            $paramKey = $parameterPair[0];
            $paramValue = $parameterPair[1];

            $this->entityParams[$paramKey] = $paramValue;
        }
    }

    protected function prepareNotesData($updateEvents): array
    {
        $notesDataArray = [];

        foreach ($updateEvents as $event) {
            $eventType = $event["type"];
            $fields = $event["value_after"][0];

            foreach ($fields as $field_type => $field) {

                $fieldId = null;

                $fieldValue = null;

                if ($field_type === "custom_field_value") {
                    $fieldId = $field["field_id"];

                    foreach ($field as $key => $val) {
                        if (!in_array($key, ['field_id', 'field_type', 'enum_id'])) {
                            $fieldValue = $val;
                        }
                    }
                } else {
                    foreach ($field as $field_name => $field_val) {
                        $fieldId = $field_name;

                        $fieldValue = $field_val;
                    }
                }

                if (!empty($fieldId) && !empty($fieldValue)) {
                    $notesDataArray[] = (object)[
                        "entity_id" => $this->entity->getId(),
                        "note_type" => "common",
                        "params" => (object)[
                            "text" => "Изменение: {$eventType}. Поле {$field_type} ({$fieldId}): {$fieldValue}. Дата: {$this->entity->getUpdatedAtFormatted()}"
                        ]
                    ];
                }
            }
        }

        return $notesDataArray;
    }
}
