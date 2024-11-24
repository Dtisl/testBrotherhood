<?php

namespace App\Controller;

use App\DTO\DeveloperDTO;
use App\Entity\Developer;
use App\Entity\Project;
use App\Service\DeveloperService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;

class DeveloperController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/developer/create', name: 'developer_create', methods: ['POST'])]
    public function create(Request $request, ValidatorInterface $validator, DeveloperService $developerService): Response
    {
        $data = json_decode($request->getContent(), true);

        $project = $this->entityManager->getRepository(Project::class)->find($data['project_id']);

        if (!$project) {
            return $this->json(['error' => 'Project does not exist'], Response::HTTP_NOT_FOUND);
        }

        $developerDTO = new DeveloperDTO(
            $data['full_name'],
            $data['email'],
            $data['position'],
            $data['contact_phone'],
            $project
        );

        $errors = $validator->validate($developerDTO);

        if (count($errors) > 0) {
            return $this->json(['errors' => (string)$errors], Response::HTTP_BAD_REQUEST);
        }

        try {
            $developer = $developerService->createDeveloper($developerDTO);
            return $this->json(['message' => 'Developer created successfully', 'developer' => $developer], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/developer/{developerId}/transfer', name: 'developer_transfer', methods: ['PUT'])]
    public function transferDeveloper(int $developerId, Request $request, DeveloperService $developerService): Response
    {
        $developer = $this->entityManager->getRepository(Developer::class)->find($developerId);

        if (!$developer) {
            return $this->json(['error' => 'Developer not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $newProject = $this->entityManager->getRepository(Project::class)->find($data['new_project_id']);

        if (!$newProject) {
            return $this->json(['error' => 'New project not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $developerService->transferDeveloperToProject($developer, $newProject);
            return $this->json(['message' => 'Developer successfully transferred to the new project'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/developer/{developerId}/update-position', name: 'developer_update_position', methods: ['PUT'])]
    public function updatePosition(int $developerId, Request $request, DeveloperService $developerService): Response
    {
        $developer = $this->entityManager->getRepository(Developer::class)->find($developerId);

        if (!$developer) {
            return $this->json(['error' => 'Developer not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['position'])) {
            try {
                $developerService->updateDeveloperPosition($developer, $data['position']);
                return $this->json(['message' => 'Position updated successfully'], Response::HTTP_OK);
            } catch (\Exception $e) {
                return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return $this->json(['error' => 'Position is required'], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/developer/{developerId}/dismiss', name: 'developer_dismiss', methods: ['DELETE'])]
    public function dismissDeveloper(int $developerId, DeveloperService $developerService): Response
    {
        $developer = $this->entityManager->getRepository(Developer::class)->find($developerId);

        if (!$developer) {
            return $this->json(['error' => 'Developer not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $developerService->dismissDeveloperFromProject($developer);
            return $this->json(['message' => 'Developer successfully dismissed from the project'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
