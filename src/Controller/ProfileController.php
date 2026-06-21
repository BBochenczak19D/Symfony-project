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

use App\Form\Type\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ProfileController.
 */
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    /**
     * Edit profile action.
     *
     * @param Request                     $request        HTTP request
     * @param EntityManagerInterface      $entityManager  Entity manager
     * @param UserPasswordHasherInterface $passwordHasher Password hasher
     * @param TranslatorInterface         $translator     Translator
     *
     * @return Response HTTP response
     */
    #[Route('/profile', name: 'app_profile', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, TranslatorInterface $translator): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user, [
            'require_password' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $user->setPassword(
                    $passwordHasher->hashPassword($user, $plainPassword)
                );
            }

            $entityManager->flush();
            $this->addFlash('success', $translator->trans('message.edited_successfully'));

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('security/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
