<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Project;
use Doctrine\ORM\EntityRepository;

class ProjectRepository extends EntityRepository
{
    public function getTotalProjects(): int
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder->select('COUNT(p.id)');

        $result = $queryBuilder->getQuery()->getSingleScalarResult();

        return (int)$result;
    }

    public function getProjectsByCustomer(): array
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder
            ->select('p.customer, COUNT(p.id) AS total_projects')
            ->groupBy('p.customer');

        return $queryBuilder->getQuery()->getResult();
    }
}
