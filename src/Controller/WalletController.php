<?php

namespace App\Controller;

use App\Repository\OperationRepository;
use App\Repository\WalletRepository;
use App\Service\WalletService;
use App\Service\WalletServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for wallet-related actions.
 */
#[Route('/wallet')]
class WalletController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param WalletService $walletService Wallet service
     */
    public function __construct(private readonly WalletServiceInterface $walletService)
    {
    }

    /**
     * Displays list of all wallets.
     * @param int $page
     * @return Response
     */
    #[Route(
        name: 'wallet_index',
        methods: ['GET'],
    )]
    public function index(#[MapQueryParameter] int $page = 1): Response
    {
        return $this->render('wallet/index.html.twig', [
            'pagination' => $this->walletService->getPaginatedList($page),
            'totals' => $this->walletService->getOperationTotals(),
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
    public function view(WalletRepository $repository, OperationRepository $operationRepository, int $id): Response
    {
        $wallet = $repository->find($id);

        if (!$wallet) {
            throw $this->createNotFoundException('Nie ma takiego portfela');
        }

        $operation = $operationRepository->findBy(['wallet' => $wallet]);

        return $this->render('wallet/view.html.twig', [
            'wallet' => $wallet,
            'operation' => $operation,
        ]);
    }
}
