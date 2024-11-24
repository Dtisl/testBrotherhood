<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class StatisticsService
{
    private $conn;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->conn = $entityManager->getConnection();
    }

    public function getTotalProjects(): int
    {
        $sql = 'SELECT COUNT(*) AS total_projects FROM project';
        $result = $this->conn->fetchAssociative($sql);
        return (int)$result['total_projects'];
    }

    public function getTotalDevelopers(): int
    {
        $sql = 'SELECT COUNT(*) AS total_developers FROM developer';
        $result = $this->conn->fetchAssociative($sql);
        return (int)$result['total_developers'];
    }

    public function getDevelopersByProject(): array
    {
        $sql = 'SELECT project_id, COUNT(*) AS total_developers FROM developer GROUP BY project_id';
        return $this->conn->fetchAllAssociative($sql);
    }

    public function getProjectsByCustomer(): array
    {
        $sql = 'SELECT customer, COUNT(*) AS total_projects FROM project GROUP BY customer';
        return $this->conn->fetchAllAssociative($sql);
    }
}
