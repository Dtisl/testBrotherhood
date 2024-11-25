<?php

namespace App\Dto;

class ProjectDTO
{
    private $name;
    private $customer;

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
