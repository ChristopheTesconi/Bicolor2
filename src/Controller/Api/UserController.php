<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    //SHOW
    #[Route('/api/{id}/user', name: 'app_api_user_getUserById', methods: ['GET'])]
    public function getUserById(UserRepository $userRepository, Int $id): JsonResponse
    {
        $user = $userRepository->find($id);
        //si pas de user, je renvois une 404 propre en json
        if (!$user) {
            return $this->json(["error" => "User not found"], Response::HTTP_NOT_FOUND);
        }

        // on ne revoit pas une vue mais un json
        return $this->json($user, Response::HTTP_OK, []);
    }

    //CREATE
    #[Route('/api/user/add', name: 'app_api_add_user', methods: ['POST'])]
    public function postUsers(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        // je récupere un json en brut
        $jsonContent = $request->getContent();
        // ! potentiellement j'ai une erreur si le json n'est pas bon
        // je transforme ce json en entité user
        try {
            $user = $serializer->deserialize($jsonContent, User::class, 'json');
        } catch (NotEncodableValueException $e) {
            // ! je gere le cas ou il ya l'erreur
            return $this->json(["error" => "JSON INVALID"], Response::HTTP_BAD_REQUEST);
        }
        // je detecte les erreurs sur mon entité avant de la persister
        $errors = $validator->validate($user);
        // on renvoi un json avec les erreurs
        if (count($errors) > 0) {
            // je crée un nouveau tableau d'erreur
            $dataErrors = [];

            foreach ($errors as $error) {
                // j'injecte dans le tableau à l'index de l'input, les messages d'erreurs qui concernent l'erreur en question
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }
            // je retourne le json avec mes erreurs
            return $this->json($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        //Cryptage du mot de passe avec passwordHasher
        $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        // on oublie pas de persist et de flush
        $entityManager->persist($user);
        $entityManager->flush();
        // on ne renvoit pas une vue mais un json
        return $this->json([$user], Response::HTTP_CREATED, [
            "Location" => $this->generateUrl("app_api_user", ["id" => $user->getId()])
        ]);
    } 


    //READ
    #[Route('/api/user', name: 'app_api_user', methods: ['GET'])]
    public function getUsers(UserRepository $userRepository): JsonResponse
    {
        // on récupère tous les utilisateurs en BDD
        $users = $userRepository->findAll();

        return $this->json($users, Response::HTTP_OK, []);
    }

    //UPDATE
    #[Route('/api/{id}/user', name: 'app_api_update_user', methods: ['PATCH'])]
    public function patchUser(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager, $id): JsonResponse
    {
        //Je récupère l'utilisateur existant en fonction de l'ID fournit dans l'URL
        $user = $entityManager->getRepository(User::class)->find($id);

        //Je vérifie si l'utilisateur existe
        if (!$user) {
            return $this->json(["error" => "User not found"], Response::HTTP_NOT_FOUND);
        }

        //Je récupère le contenu json de la requete 
        $jsonContent = $request->getContent();

        //Je désérialise le json pour mettre à jour l'utilisateur
        try {
            $updatedUser = $serializer->deserialize($jsonContent, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);
        } catch (NotEncodableValueException $e) {
            return $this->json(["error" => "JSON INVALID"], Response::HTTP_BAD_REQUEST);
        }

        //Vérification des erreurs de validation
        $errors = $validator->validate($updatedUser);

        //Je vérifie si il y a des erreurs de validation
        if (count($errors) > 0) {
            $dataErrors = [];

            //Je rassemble les erreurs de validation pour chaque champs
            foreach ($errors as $error) {
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }
            //Je retourne une réponse JSON avec les erreurs de validation
            return $this->json($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        //Je sauvegarde des données mises à jour dans la base de données
        $entityManager->persist($user);
        $entityManager->flush();

        // Retour d'une réponse JSON avec les données mises à jour de l'utilisateur et redirection vers la route user
        return $this->json([$updatedUser], Response::HTTP_OK, [
            "Location" => $this->generateUrl("app_api_user", ["id" => $updatedUser->getId()])
        ]);
    }

    //DELETE
    #[Route('/api/{id}/user', name: 'app_api_delete_user', methods: ['DELETE'])]
    public function deleteUser(UserRepository $userRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        // récuperer utilisateur par son Id
        $user = $userRepository->find($id);

        //Je vérifie si l'utilisateur existe / si il n'existe pas on renvoi un message d'erreur
        if (!$user) {
            return $this->json(["error" => "User not found"], Response::HTTP_NOT_FOUND);
        }
        
        // suppression utilisateur en BDD
        $entityManager->remove($user);
        $entityManager->flush();

        // Retour d'une réponse JSON avec les deletes  mises à jour
        return $this->json(["message" => "User deleted successfully"], Response::HTTP_OK);
    }
}
