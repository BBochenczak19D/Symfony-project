<?php

namespace App\Controller;

use App\Entity\Operation;
use App\Entity\Wallet;
use App\Form\Type\OperationType;
use App\Form\Type\WalletType;
use App\Service\WalletServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * Controller for wallet-related actions.
 *
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
    public function __construct(private readonly WalletServiceInterface $walletService, private readonly TranslatorInterface $translator)
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
        #[MapQueryParameter] int $page = 1,
    ): Response {
        $wallet = $this->walletService->findById($id);

        if (!$wallet) {
            throw $this->createNotFoundException('Nie ma takiego portfela');
        }

        return $this->render('wallet/view.html.twig', [
            'wallet' => $wallet,
            'pagination' => $this->walletService->getPaginatedOperations($id, $page),
            'totals' => $this->walletService->getOperationTotals(),
        ]);
    }

    /**
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route(
        '/{id}/add-operation',
        name: 'add_operation',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST'],
    )]
    public function addOperation(int $id, Request $request, EntityManagerInterface $entityManager): Response
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

            return $this->redirectToRoute('wallet_view', ['id' => $wallet->getId()]);
        }

        return $this->render(
            'wallet/add-operation.html.twig',
            ['form' => $form->createView(),
                'wallet' => $wallet, ]
        );
    }

    /**
     * @param Request $request
     * @param Operation $operation
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route(
        '/{walletId}/operation-{id}/delete',
        name: 'delete_operation',
        requirements: ['walletId' => '[1-9]\d*', 'id' => '[1-9]\d*'],
        methods: ['GET', 'POST'],
    )]
    public function deleteOperation(Request $request,int $walletId, Operation $operation, EntityManagerInterface $entityManager): Response
    {
        $wallet = $operation->getWallet();
        $form = $this->createForm(FormType::class, null, [
            'method' => 'POST',
            'action' => $this->generateUrl('delete_operation', [
                'walletId' => $walletId,
                'id' => $operation->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($operation);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('wallet_view', ['id' => $wallet->getId()]);
        }

        return $this->render(
            'wallet/delete-operation.html.twig',
            [
                'form' => $form->createView(),
                'wallet' => $wallet,
            ]
        );
    }

    #[Route(
        '/{walletId}/operation-{id}/edit',
        name: 'edit_operation',
        requirements: ['walletId' => '[1-9]\d*', 'id' => '[1-9]\d*'],
        methods: ['GET', 'POST'],
    )]
    public function edit(Request $request,int $walletId, Operation $operation, EntityManagerInterface $entityManager): Response
    {
        $wallet = $operation->getWallet();

        $form = $this->createForm(OperationType::class, $operation, [
            'method' => 'POST',
            'action' => $this->generateUrl('edit_operation', [
                'walletId' => $walletId,
                'id' => $operation->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('wallet_view', ['id' => $wallet->getId()]);
        }

        return $this->render(
            'wallet/edit-operation.html.twig',
            [
                'form' => $form->createView(),
                'operation' => $operation,
                'wallet' => $wallet,
            ]
        );
    }

    /**
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route(
        '/add-wallet',
        name: 'add_wallet',
        methods: ['GET', 'POST'],
    )]
    public function addWallet(Request $request, EntityManagerInterface $entityManager): Response
    {
        $wallet = new Wallet();

        $form = $this->createForm(WalletType::class, $wallet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($wallet);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('wallet_index');
        }

        return $this->render(
            'wallet/add-wallet.html.twig',
            ['form' => $form->createView()]
        );
    }
}
