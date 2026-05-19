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
     * @param OperationRepository $operationRepository
     * @return Response
     */
    #[Route(
        name: 'wallet_index',
        methods: ['GET'],
    )]
    public function index( OperationRepository $operationRepository, #[MapQueryParameter] int $page = 1): Response
    {
        $pagination = $this->walletService->getPaginatedList($page);

        // $wallets = $walletRepository->findAll();
        // $operation = $operationRepository->findByExampleField();

        $totals = [];
        foreach ($operationRepository->findByExampleField() as $dto) {
            $totals[$dto->getId()] = $dto->getAmount();
        }

        return $this->render('wallet/index.html.twig', [
            'pagination' => $pagination,
            'totals' => $totals,
        ]);
        /*return $this->render('wallet/index.html.twig', [
            'wallets' => $wallets,
            'operation' => $operation,
        ]);*/
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
