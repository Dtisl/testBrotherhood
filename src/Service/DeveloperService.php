<?php

namespace App\Service;

use App\DTO\DeveloperDTO;
use App\Entity\Developer;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;

class DeveloperService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createDeveloper(DeveloperDTO $developerDTO): Developer
    {
        $project = $developerDTO->getProject();

        $developer = new Developer();
        $developer->setFullName($developerDTO->getFullName());
        $developer->setEmail($developerDTO->getEmail());
        $developer->setPosition($developerDTO->getPosition());
        $developer->setContactPhone($developerDTO->getContactPhone());
        $developer->setProject($project);

        $this->entityManager->persist($developer);
        $this->entityManager->flush();

        return $developer;
    }

    public function transferDeveloperToProject(Developer $developer, Project $newProject): void
    {
        $developer->setProject($newProject);
        $this->entityManager->flush();
    }

    public function updateDeveloperPosition(Developer $developer, string $newPosition): void
    {
        $developer->setPosition($newPosition);
        $this->entityManager->flush();
    }

    public function dismissDeveloperFromProject(Developer $developer): void
    {
        $developer->setProject(null);
        $this->entityManager->flush();
    }
}
