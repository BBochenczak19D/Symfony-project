<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\Type\CategoryType;
use App\Security\Voter\CategoryVoter;
use App\Service\CategoryServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/category')]
class CategoryController extends AbstractController
{
    public function __construct(
        private readonly CategoryServiceInterface $categoryService,
        private readonly TranslatorInterface $translator,
    ) {
    }

    /**
     * @param int $page
     * @return Response
     */
    #[Route(
        name: 'category_index',
        methods: ['GET'],
    )]
    public function index(#[MapQueryParameter] int $page = 1): Response
    {
        $author = $this->getUser();

        return $this->render('category/index.html.twig', [
            'pagination' => $this->categoryService->getPaginatedList($page, $author), ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route(
        '/add-category',
        name: 'add_category',
        methods: ['GET', 'POST'],
    )]
    public function addCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $category = new Category();
        $category->setAuthor($user);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'category/add-category.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @param Request $request
     * @param Category $category
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route(
        '/{id}/delete',
        name: 'delete_category',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST'],
    )]
    #[IsGranted(CategoryVoter::DELETE, subject: 'category')]
    public function deleteCategory(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FormType::class, null, [
            'method' => 'POST',
            'action' => $this->generateUrl('delete_category', [
                'id' => $category->getId(),
            ]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->delete($category);
            $this->addFlash('success', $this->translator->trans('message.edited_successfully'));

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'category/delete-tag.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }

    /**
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    #[Route(
        '/{id}/edit',
        name: 'edit_category',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST'],
    )]
    #[IsGranted(CategoryVoter::EDIT, subject: 'category')]
    public function editCategory(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category, [
            'method' => 'POST',
            'action' => $this->generateUrl('edit_category', [
                'id' => $category->getId(),
            ]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->save($category);
            $this->addFlash('success', $this->translator->trans('message.created_successfully'));

            return $this->redirectToRoute('category_index', ['id' => $category->getId()]);
        }

        return $this->render(
            'category/edit-category.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }
}
