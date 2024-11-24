<?php

namespace App\Service;

use App\DTO\ProjectDTO;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
