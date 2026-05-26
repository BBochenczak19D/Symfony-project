<?php

namespace App\Controller;

use App\Entity\Operation;
use App\Form\Type\OperationType;
use App\Service\WalletServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * Controller for wallet-related actions.
 * @method addFlash(string $string, $trans)
 */
#[Route('/wallet')]
class WalletController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param WalletService $walletService Wallet service
     */
    public function __construct(private readonly WalletServiceInterface $walletService,private readonly TranslatorInterface $translator)
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

    /**
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param WalletService $wallet
     * @return Response
     */
    #[Route(
        '/{id}/add-operation',
        name: 'add_operation',
        methods: ['GET','POST'],
        requirements: ['id' => '[1-9]\d*'],
    )]
    public function addOperation(int $id, Request $request, EntityManagerInterface $entityManager) : Response
    {
        $wallet = $this->walletService->findById($id);

        if (!$wallet) {
            throw $this->createNotFoundException('Nie ma takiego portfela');
        }

        $operation = new Operation();
        $operation->setWallet($wallet);

        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($operation);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('wallet_view',['id' => $wallet->getId()]);
        }

        return $this->render(
            'wallet/add-operation.html.twig',
            ['form' => $form->createView(),
                'wallet' => $wallet,]
        );
    }
}
