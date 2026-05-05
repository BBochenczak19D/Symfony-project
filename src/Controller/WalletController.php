<?php

namespace App\Controller;

use App\Repository\WalletRepository;
use App\Repository\OperationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for wallet-related actions.
 */
#[Route('/wallet')]
class WalletController extends AbstractController
{
    /**
     * Displays list of all wallets.
     */
    #[Route(
        name: 'wallet_index',
        methods: ['GET'],
    )]
    public function index(WalletRepository $repository): Response
    {
        $wallets = $repository->findAll();

        return $this->render('wallet/index.html.twig', [
            'wallets' => $wallets,
        ]);
    }

    /**
     * Displays a single wallet by ID.
     */
    #[Route(
        '/{id}',
        name: 'wallet_view',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET'],
    )]
    public function view(WalletRepository $repository,OperationRepository $operationRepository, int $id): Response
    {
        $wallet = $repository->find($id);

        if (!$wallet) {
            throw $this->createNotFoundException('Nie ma takiego portfela');
        }

        $operation = $operationRepository->findBy(['wallet' => $wallet]);

        return $this->render('wallet/view.html.twig', [
            'wallet' => $wallet,
            'operation' => $operation
        ]);
    }
}
