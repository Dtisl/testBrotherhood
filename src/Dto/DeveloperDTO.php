<?php

namespace App\Dto;

use App\Entity\Project;

class DeveloperDTO
{
    private string $fullName;
    private string $email;
    private string $position;
    private string $contactPhone;
    private int $projectId;

    public function __construct(
        string $fullName,
        string $email,
        string $position,
        string $contactPhone,
        int $projectId
    )
    {
        $this->fullName = $fullName;
        $this->email = $email;
        $this->position = $position;
        $this->contactPhone = $contactPhone;
        $this->projectId = $projectId;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function getContactPhone(): string
    {
        return $this->contactPhone;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }
}
