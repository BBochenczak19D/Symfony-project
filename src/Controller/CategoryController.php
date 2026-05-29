<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\Type\CategoryType;
use App\Service\CategoryServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 *
 */
#[Route('/category')]
class CategoryController extends AbstractController
{
    /**
     * @param CategoryServiceInterface $categoryService
     * @param TranslatorInterface $translator
     */
    public function __construct(
        private readonly CategoryServiceInterface $categoryService,
        private readonly TranslatorInterface $translator
    )
    {
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
        return $this->render('category/index.html.twig', [
            'pagination' => $this->categoryService->getPaginatedList($page)]);
    }

    #[Route(
        '/add-category',
    name: 'add_category',
    methods: ['GET', 'POST'],
    )]
    public function addCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
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

    #[Route(
        '/{id}/delete',
        name: 'delete_category',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST'],
    )]
    public function deleteCategory(Request $request,Category $category, EntityManagerInterface $entityManager): Response
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
            'category/delete-category.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }
}
