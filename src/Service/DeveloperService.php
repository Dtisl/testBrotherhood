<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\DeveloperDTO;
use App\Entity\Developer;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeveloperService
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function createDeveloper(DeveloperDTO $developerDTO): Developer
    {
        $project = $this
            ->entityManager
            ->getRepository(Project::class)
            ->find($developerDTO->getProjectId());

        if (!$project) {
            throw new NotFoundHttpException('Project does not exist');
        }

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

    public function transferDeveloperToProject(int $developerId, ?int $newProjectId): void
    {
        $developer = $this
            ->entityManager
            ->getRepository(Developer::class)
            ->find($developerId);

        if (!$developer) {
            throw new NotFoundHttpException('Developer not found');
        }

        if (!$newProjectId) {
            throw new InvalidArgumentException('New project ID is required');
        }

        $newProject = $this
            ->entityManager
            ->getRepository(Project::class)
            ->find($newProjectId);

        if (!$newProject) {
            throw new NotFoundHttpException('New project not found');
        }

        $developer->setProject($newProject);
        $this->entityManager->flush();
    }

    public function updateDeveloperPosition(int $developerId, ?string $newPosition): void
    {
        $developer = $this
            ->entityManager
            ->getRepository(Developer::class)
            ->find($developerId);

        if (!$developer) {
            throw new NotFoundHttpException('Developer not found');
        }

        if (!$newPosition) {
            throw new InvalidArgumentException('Position is required');
        }

        $developer->setPosition($newPosition);
        $this->entityManager->flush();
    }

    public function dismissDeveloperFromProject(int $developerId): void
    {
        $developer = $this
            ->entityManager
            ->getRepository(Developer::class)
            ->find($developerId);

        if (!$developer) {
            throw new NotFoundHttpException('Developer not found');
        }

        $developer->setProject(null);
        $this->entityManager->flush();
    }
}
