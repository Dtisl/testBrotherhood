<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Developer;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;

class StatisticsService
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getTotalProjects(): int
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('COUNT(p.id)')
            ->from(Project::class, 'p');

        $result = $queryBuilder->getQuery()->getSingleScalarResult();

        return (int) $result;
    }

    public function getTotalDevelopers(): int
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('COUNT(d.id)')
            ->from(Developer::class, 'd');

        $result = $queryBuilder->getQuery()->getSingleScalarResult();

        return (int) $result;
    }

    public function getDevelopersByProject(): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('d.project, COUNT(d.id) AS total_developers')
            ->from(Developer::class, 'd')
            ->groupBy('d.project');

        return $queryBuilder->getQuery()->getResult();
    }

    public function getProjectsByCustomer(): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('p.customer, COUNT(p.id) AS total_projects')
            ->from(Project::class, 'p')
            ->groupBy('p.customer');

        return $queryBuilder->getQuery()->getResult();
    }
}
