<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class DeveloperDTO
{
    #[Assert\NotBlank(message: "Поле 'fullName' не может быть пустым.")]
    #[Assert\Length(min: 3, max: 255, minMessage: "Полное имя должно быть не короче 3 символов.", maxMessage: "Полное имя не может быть длиннее 255 символов.")]
    public string $fullName;

    #[Assert\NotBlank(message: "Поле 'email' не может быть пустым.")]
    #[Assert\Email(message: "Неверный формат адреса электронной почты.")]
    public string $email;

    #[Assert\NotBlank(message: "Поле 'position' не может быть пустым.")]
    #[Assert\Length(min: 2, max: 100, minMessage: "Должность должна быть не короче 2 символов.", maxMessage: "Должность не может быть длиннее 100 символов.")]
    public string $position;

    #[Assert\NotBlank(message: "Поле 'contactPhone' не может быть пустым.")]
    #[Assert\Regex(pattern: "/^\+?\d{10,15}$/", message: "Неверный формат телефона.")]
    public string $contactPhone;

    #[Assert\NotBlank(message: "Поле 'projectId' не может быть пустым.")]
    #[Assert\Type(type: 'integer', message: "Поле 'projectId' должно быть целым числом.")]
    public int $projectId;

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
