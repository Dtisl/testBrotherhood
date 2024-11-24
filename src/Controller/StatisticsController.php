<?php

namespace App\Controller;

use App\Service\StatisticsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StatisticsController extends AbstractController
{
    private $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    #[Route('/statistics/projects', name: 'statistics_projects', methods: ['GET'])]
    public function getTotalProjects(): JsonResponse
    {
        $totalProjects = $this->statisticsService->getTotalProjects();
        return $this->json(['total_projects' => $totalProjects]);
    }

    #[Route('/statistics/developers', name: 'statistics_developers', methods: ['GET'])]
    public function getTotalDevelopers(): JsonResponse
    {
        $totalDevelopers = $this->statisticsService->getTotalDevelopers();
        return $this->json(['total_developers' => $totalDevelopers]);
    }

    #[Route('/statistics/developers-by-project', name: 'statistics_developers_by_project', methods: ['GET'])]
    public function getDevelopersByProject(): JsonResponse
    {
        $developersByProject = $this->statisticsService->getDevelopersByProject();
        return $this->json(['developers_by_project' => $developersByProject]);
    }

    #[Route('/statistics/projects-by-customer', name: 'statistics_projects_by_customer', methods: ['GET'])]
    public function getProjectsByCustomer(): JsonResponse
    {
        $projectsByCustomer = $this->statisticsService->getProjectsByCustomer();
        return $this->json(['projects_by_customer' => $projectsByCustomer]);
    }
}
