<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\UserManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route('/users'), IsGranted('ROLE_ADMIN')]
class UserController extends AbstractController
{
    
    #[Route('/', name: 'user_list')]
    public function listAction(UserRepository $userRepository) : Response
    {
        return $this->render('user/list.html.twig', ['users' => $userRepository->findAll()]);
    }

    #[Route('/create', methods: ['GET', 'POST'], name: 'user_create')]
    public function createAction(Request $request, UserManagerInterface $userManager) : Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
            try {
                $userManager->userForm($form, $user);
            } catch (Exception $exception) {
                $this->addFlash('error', $exception->getMessage() . 'Erreur Système, veuillez ré-essayer');
                return $this->redirectToRoute('homepage');
            }

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/{id}/edit', methods: ['GET', 'POST'], name: 'user_edit')]
    public function editAction(User $user, Request $request, UserManagerInterface $userManager) : Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $userManager->userForm($form, $user);
            } catch (Exception $exception) {
                $this->addFlash('error', $exception->getMessage() . 'Erreur Système, veuillez ré-essayer');
                return $this->redirectToRoute('homepage');
            }

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}