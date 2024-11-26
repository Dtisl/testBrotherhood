<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class DeveloperRepository extends EntityRepository
{
    public function getTotalDevelopers(): int
    {
        $queryBuilder = $this->createQueryBuilder('d');
        $queryBuilder->select('COUNT(d.id)');

        $result = $queryBuilder->getQuery()->getSingleScalarResult();

        return (int)$result;
    }

    public function getDevelopersByProject(): array
    {
        $queryBuilder = $this->createQueryBuilder('d');
        $queryBuilder
            ->select('d.project, COUNT(d.id) AS total_developers')
            ->groupBy('d.project');

        return $queryBuilder->getQuery()->getResult();
    }
}
