<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ProjectDTO;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function createProject(ProjectDTO $projectDTO): Project
    {
        $project = new Project();
        $project->setName($projectDTO->getName());
        $project->setCustomer($projectDTO->getCustomer());

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $project;
    }

    public function deleteProject(Project $project): void
    {
        foreach ($project->getDevelopers() as $developer) {
            $developer->setProject(null);
        }

        $this->entityManager->remove($project);
        $this->entityManager->flush();
    }
}
