<?php

declare(strict_types=1);

namespace AmoEntity;

use AmoEntity\Entity;

class LeadEntity extends Entity
{
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setResponsibleUserId(int $id): void
    {
        $this->responsibleUserId = $id;
    }

    public function getResponsibleUserId(): int
    {
        return $this->responsibleUserId;
    }

    public function getResponsibleUserName(): string
    {
        return $this->responsibleUserName;
    }

    public function setResponsibleUserName(string $name): void
    {
        $this->responsibleUserName = $name;
    }

    public function setCreatedAt(int $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(int $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getUpdatedAt(): int
    {
        return $this->updatedAt;
    }

    public function getCreatedAtFormatted(): string
    {
        return (new \DateTime())
            ->setTimestamp($this->getCreatedAt())
            ->format("d.m.Y H:i");
    }

    public function getUpdatedAtFormatted(): string
    {
        return (new \DateTime())
            ->setTimestamp($this->getUpdatedAt())
            ->format("d.m.Y H:i");
    }
}
