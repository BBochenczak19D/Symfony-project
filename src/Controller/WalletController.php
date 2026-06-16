<?php

/**
 * This file is part of the SI project.
 *
 * (c) Students
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Operation;
use App\Entity\Wallet;
use App\Form\Type\OperationType;
use App\Form\Type\WalletType;
use App\DTO\OperationListInputFiltersDTO;
use App\Service\WalletServiceInterface;
use App\Service\OperationServiceInterface;
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
     * @param WalletServiceInterface    $walletService
     * @param OperationServiceInterface $operationService
     * @param TranslatorInterface       $translator
     */
    public function __construct(private readonly WalletServiceInterface $walletService, private readonly OperationServiceInterface $operationService, private readonly TranslatorInterface $translator)
    {
    }


    /**
     * @param int $page
     *
     * @return Response
     */
    #[Route(
        name: 'wallet_index',
        methods: ['GET'],
    )]
    #[IsGranted(WalletVoter::VIEW, subject: 'wallet')]
    public function index(#[MapQueryParameter] int $page = 1): Response
    {
        $author = $this->getUser();
        $pagination = $this->walletService->getPaginatedList($page, $author);

        return $this->render('wallet/index.html.twig', [
            'pagination' => $pagination,
            'totals' => $this->walletService->getOperationTotals(),
        ]);
    }

    /**
     * @param Wallet                       $wallet
     * @param OperationListInputFiltersDTO $filters
     * @param int                          $page
     *
     * @return Response
     */
    #[Route(
        '/{id}',
        name: 'wallet_view',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET'],
    )]
    #[IsGranted(OperationVoter::VIEW, subject: 'operation')]
    public function view(Wallet $wallet, #[MapQueryString(resolver: OperationListInputFiltersDTOResolver::class)] OperationListInputFiltersDTO $filters, #[MapQueryParameter] int $page = 1): Response
    {
        return $this->render('wallet/view.html.twig', [
            'wallet' => $wallet,
            'pagination' => $this->walletService->getPaginatedOperations($wallet->getId(), $page, $filters),
            'totals' => $this->walletService->getOperationTotals(),
        ]);
    }

    /**
     * @param int                    $id
     * @param Request                $request
     * @param EntityManagerInterface $entityManager
     *
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
            if (!$this->walletService->canAddAmount($wallet->getId(), (float) $operation->getAmount())) {
                $this->addFlash('danger', 'Saldo nie może spaść poniżej 0.');

                return $this->render('wallet/add-operation.html.twig', [
                    'form' => $form->createView(),
                    'wallet' => $wallet,
                ]);
            }
            $this->operationService->save($operation);
            $this->addFlash('success', $this->translator->trans('message.created_successfully'));

            return $this->redirectToRoute('wallet_view', ['id' => $wallet->getId()]);
        }

        return $this->render(
            'wallet/add-operation.html.twig',
            ['form' => $form->createView(),
                'wallet' => $wallet, ]
        );
    }

    /**
     * @param Request                $request
     * @param int                    $walletId
     * @param Operation              $operation
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    #[Route(
        '/{walletId}/operation-{id}/delete',
        name: 'delete_operation',
        requirements: ['walletId' => '[1-9]\d*', 'id' => '[1-9]\d*'],
        methods: ['GET', 'POST'],
    )]
    #[IsGranted(OperationVoter::DELETE, subject: 'operation')]
    public function deleteOperation(Request $request, int $walletId, Operation $operation, EntityManagerInterface $entityManager): Response
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
            $this->operationService->save($operation);
            $this->addFlash('success', $this->translator->trans('message.edited_successfully'));

            return $this->redirectToRoute('wallet_view', ['id' => $wallet->getId()]);
        }

        return $this->render(
            'wallet/delete-tag.html.twig',
            [
                'form' => $form->createView(),
                'wallet' => $wallet,
            ]
        );
    }

    /**
     * @param Request                $request
     * @param int                    $walletId
     * @param Operation              $operation
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    #[Route(
        '/{walletId}/operation-{id}/edit',
        name: 'edit_operation',
        requirements: ['walletId' => '[1-9]\d*', 'id' => '[1-9]\d*'],
        methods: ['GET', 'POST'],
    )]
    #[IsGranted(OperationVoter::EDIT, subject: 'operation')]
    public function edit(Request $request, int $walletId, Operation $operation, EntityManagerInterface $entityManager): Response
    {
        $wallet = $operation->getWallet();
        $oldAmount = (float) $operation->getAmount();

        $form = $this->createForm(OperationType::class, $operation, [
            'method' => 'POST',
            'action' => $this->generateUrl('edit_operation', [
                'walletId' => $walletId,
                'id' => $operation->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $entityManager->flush();
            if (!$this->walletService->canAddAmount($wallet->getId(), (float) $operation->getAmount(), $oldAmount)) {
                $this->addFlash('danger', 'Saldo nie może spaść poniżej 0.');

                return $this->render('wallet/edit-category.html.twig', [
                    'form' => $form->createView(),
                    'operation' => $operation,
                    'wallet' => $wallet,
                ]);
            }
            $this->operationService->save($operation);
            $this->addFlash('success', $this->translator->trans('message.created_successfully'));

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
     * @param Request                $request
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    #[Route(
        '/add-wallet',
        name: 'add_wallet',
        methods: ['GET', 'POST'],
    )]
    public function addWallet(Request $request, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        $wallet = new Wallet();
        $wallet->setAuthor($user);

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

    /**
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    #[Route(
        '/{id}/edit-wallet',
        name: 'edit_wallet',
        methods: ['GET', 'POST'],
        requirements: ['id' => '[1-9]\d*'],
    )]
    #[IsGranted(WalletVoter::EDIT, subject: 'wallet')]
    public function editWallet(Request $request, int $id): Response
    {
        $wallet = $this->walletService->findById($id);
        if (!$wallet) {
            throw $this->createNotFoundException('Nie ma takiego portfela');
        }

        $form = $this->createForm(WalletType::class, $wallet, [
            'method' => 'POST',
            'action' => $this->generateUrl('edit_wallet', ['id' => $wallet->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->walletService->save($wallet);
            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('wallet_index');
        }

        return $this->render(
            'wallet/add-wallet.html.twig',
            ['form' => $form->createView(), 'wallet' => $wallet]
        );
    }
}
