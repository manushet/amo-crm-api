<?php

declare(strict_types=1);

namespace AmoEntity;

abstract class Entity
{
    protected int $id;

    protected string $name;

    protected int $responsibleUserId;

    protected string $responsibleUserName;

    protected int $createdAt;

    protected int $updatedAt;
}
