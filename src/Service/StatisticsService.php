<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\DeveloperRepository;
use App\Repository\ProjectRepository;

class StatisticsService
{
    private DeveloperRepository $developerRepository;
    private ProjectRepository $projectRepository;

    public function __construct(DeveloperRepository $developerRepository, ProjectRepository $projectRepository)
    {
        $this->developerRepository = $developerRepository;
        $this->projectRepository = $projectRepository;
    }

    public function getTotalProjects(): int
    {
        return $this->projectRepository->getTotalProjects();
    }

    public function getTotalDevelopers(): int
    {
        return $this->developerRepository->getTotalDevelopers();
    }

    public function getDevelopersByProject(): array
    {
        return $this->developerRepository->getDevelopersByProject();
    }

    public function getProjectsByCustomer(): array
    {
        return $this->projectRepository->getProjectsByCustomer();
    }
}
