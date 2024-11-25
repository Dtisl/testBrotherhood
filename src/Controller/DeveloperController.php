<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\DeveloperDTO;
use App\Service\DeveloperService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/developer')]
class DeveloperController extends AbstractController
{
    public function __construct(
        private readonly DeveloperService   $developerService,
        private readonly ValidatorInterface $validator
    )
    {
    }

    #[Route('/create', name: 'developer_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        try {
            $developerDTO = new DeveloperDTO(
                $data['full_name'],
                $data['email'],
                $data['position'],
                $data['contact_phone'],
                $data['project_id']
            );

            $errors = $this->validator->validate($developerDTO);
            if (count($errors) > 0) {
                return $this->json(
                    ['errors' => (string)$errors],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $developer = $this
                ->developerService
                ->createDeveloper($developerDTO);

            return $this->json(
                [
                    'message' => 'Developer created successfully',
                    'developer' => $developer,
                ],
                Response::HTTP_CREATED
            );
        } catch (Exception $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/{developerId}/transfer', name: 'developer_transfer', methods: ['PUT'])]
    public function transferDeveloper(int $developerId, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $newProjectId = $data['new_project_id'] ?? null;

        try {
            $this
                ->developerService
                ->transferDeveloperToProject($developerId, $newProjectId);

            return $this->json(
                ['message' => 'Developer successfully transferred to the new project'],
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/{developerId}/update-position', name: 'developer_update_position', methods: ['PUT'])]
    public function updatePosition(int $developerId, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $newPosition = $data['position'] ?? null;

        try {
            $this
                ->developerService
                ->updateDeveloperPosition($developerId, $newPosition);

            return $this->json(
                ['message' => 'Position updated successfully'],
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/{developerId}/dismiss', name: 'developer_dismiss', methods: ['DELETE'])]
    public function dismissDeveloper(int $developerId): Response
    {
        try {
            $this
                ->developerService
                ->dismissDeveloperFromProject($developerId);

            return $this->json(
                ['message' => 'Developer successfully dismissed from the project'],
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
