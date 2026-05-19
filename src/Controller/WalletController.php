<?php

namespace App\Controller;

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
    public function view(
        int $id,
        #[MapQueryParameter] int $page = 1
    ): Response {
        $wallet = $this->walletService->findById($id);

        if (!$wallet) {
            throw $this->createNotFoundException('Nie ma takiego portfela');
        }

        return $this->render('wallet/view.html.twig', [
            'wallet'     => $wallet,
            'pagination' => $this->walletService->getPaginatedOperations($id,$page),
            'totals'     => $this->walletService->getOperationTotals(),
        ]);
    }
}
