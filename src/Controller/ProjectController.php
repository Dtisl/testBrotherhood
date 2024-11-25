<?php
declare(strict_types=1);

namespace App\Controller;

use App\Dto\ProjectDTO;
use App\Entity\Project;
use App\Service\ProjectService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/project')]
class ProjectController extends AbstractController
{
    public function __construct(private readonly ProjectService $projectService)
    {
    }

    #[Route('/create', name: 'project_create', methods: ['POST'])]
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        $data = json_decode($request->getContent(), true);

        $projectDTO = new ProjectDTO($data['name'], $data['customer']);

        $errors = $validator->validate($projectDTO);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(
                ['errors' => $errorMessages],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $project = $this
                ->projectService
                ->createProject($projectDTO);
        } catch (Exception $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return $this->json([
            'message' => 'Project created successfully!',
            'project' => $project
        ],
            Response::HTTP_CREATED
        );
    }

    #[Route('/{id}/delete', name: 'project_delete', methods: ['DELETE'])]
    public function delete(Project $project): Response
    {
        if (!$project) {
            return $this->json(
                ['error' => 'Project not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        try {
            $this
                ->projectService
                ->deleteProject($project);

            return $this->json(
                ['message' => 'Project successfully deleted'],
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
