<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/user')]
#[IsGranted('ROLE_ADMIN')]
class AdminUserController extends AbstractController
{
    private const int PAGINATOR_ITEMS_PER_PAGE = 10;

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly TranslatorInterface $translator,
        private readonly PaginatorInterface $paginator,
    ) {
    }

    /**
     * Lista użytkowników.
     */
    #[Route(
        name: 'admin_user_index',
        methods: ['GET'],
    )]
    public function index(#[MapQueryParameter] int $page = 1): Response
    {
        $queryBuilder = $this->userRepository->createQueryBuilder('u')
            ->orderBy('u.id', 'ASC');

        $pagination = $this->paginator->paginate(
            $queryBuilder,
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE,
        );

        return $this->render('security/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Edycja użytkownika (email + opcjonalnie hasło).
     */
    #[Route(
        '/{id}/edit',
        name: 'admin_user_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST'],
    )]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user, [
            'require_password' => false,
            'method' => 'POST',
            'action' => $this->generateUrl('admin_user_edit', ['id' => $user->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $user->setPassword(
                    $this->passwordHasher->hashPassword($user, $plainPassword)
                );
            }

            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('message.edited_successfully'));

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('security/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
