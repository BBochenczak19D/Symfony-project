<?php

namespace App\Controller;

use App\Repository\OperationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for wallet-related actions.
 */
#[Route('/operation')]
class OperationController extends AbstractController
{
    /**
     * Displays list of all operations.
     */
    #[Route(
        name: 'operation_index',
        methods: ['GET'],
    )]
    public function index(#[MapQueryParameter] int $page = 1): Response
    {
        return $this->render('operation/index.html.twig', [
            'pagination' => $this->operationService->getPaginatedList($page)
        ]);
    }

    /**
     * Displays a operations details
     * @param OperationRepository $repository
     * @param int $id
     * @return Response
     */
    #[Route(
        '/{id}',
        name: 'operation_view',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET'],
    )]
    public function view(OperationRepository $repository, int $id): Response
    {
        $operation = $repository->find($id);

        if (!$operation) {
            throw $this->createNotFoundException('Nie ma takiego portfela');
        }

        return $this->render('wallet/view.html.twig', [
            'operation' => $operation,
        ]);
    }
}
