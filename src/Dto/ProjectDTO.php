<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ProjectDTO
{
    #[Assert\NotBlank(message: "Поле 'name' не может быть пустым.")]
    #[Assert\Length(min: 2, max: 255, minMessage: "Имя должно быть не короче 2 символов.", maxMessage: "Имя не может быть длиннее 255 символов.")]
    public string $name;

    #[Assert\NotBlank(message: "Поле 'customer' не может быть пустым.")]
    #[Assert\Length(min: 2, max: 255, minMessage: "Имя клиента должно быть не короче 2 символов.", maxMessage: "Имя клиента не может быть длиннее 255 символов.")]
    public string $customer;

    public function __construct(string $name, string $customer)
    {
        $this->name = $name;
        $this->customer = $customer;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCustomer(): string
    {
        return $this->customer;
    }
}
