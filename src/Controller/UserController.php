<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    //Read
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    //Create
    #[Route('/user/add', name: 'app_add_user')]
    public function add(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        // J'instancie un nouvel utilisateur
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ["custom_option" => "add"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Je récupère le mot de passe en clair
            $plainPassword = $user->getPassword();
            // Je le hash
            $passwordHash = $passwordHasher->hashPassword($user, $plainPassword);
            // Je set le mot de  passe 
            $user->setPassword($passwordHash);

            $userRepository->add($user, true);
            $this->addFlash("success", "Utilisateur bien ajouté !");
            return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    //Update
    #[Route('/user/{id}/update', name: 'app_update_user')]
    public function update(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user, ["custom_option" => "update"]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);
            $this->addFlash("warning", "Utilisateur bien modifié !");
            return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/update.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    //Delete
    #[Route('/user/{id}/delete', name: 'app_delete_user')]
    public function delete(int $id, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
            $user = $userRepository->find($id);

            if (!$user) {
                throw $this->createNotFoundException('Utilisateur non trouvé');
            }
        
            $entityManager->remove($user);
            $entityManager->flush();
        
            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        
            return $this->redirectToRoute('app_user');
        
    }

    //Show
    #[Route('/user/{id}/show', name: 'app_show_user')]
    public function show(User $user)
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
}
