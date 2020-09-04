<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/** @Route("/api", name="api_")*/
class UserController extends ApiController
{
    /**
     * @Route("/users", methods="GET")
     */
    public function showAll(UserRepository $userRepository) {
        $users = $userRepository->transformAll();
        return $this->respond($users);
    }

    /**
     * @Route("/users/{id}", methods="GET")
     */
    public function show($id, UserRepository $userRepository) {

        $user = $userRepository->find($id);
        if (! $user) {
            return $this->respondNotFound();
        }
        $user = $userRepository->transform($user);

        return $this->respond($user);
    }

    /**
     * @Route("/register", methods="POST")
     */
    public function create(Request $request, UserRepository $userRepository, EntityManagerInterface $em) {
        $body = json_decode($request->getContent(), true);
        $users = $userRepository->transformAll();
        $userdb = new User;

        foreach ($users as $userdb) {
            if ($body['username'] === $userdb)
                return $this->respondValidationError('This username already exist.');
        }

        if ($body == null) {
            return $this->respondValidationError('Please provide a valid request!');
        }
        // validate the fields
        if (! $body['username']) {
            return $this->respondValidationError('Please tell me who you are!');
        }
        if (! $body['password']) {
            return $this->respondValidationError('Please provide a password!');
        }

        // persist
        $user = new User;
        $user->setUsername($body['username']);
        $user->setPassword($body['password']);

        $em->persist($user);
        $em->flush();

        return $this->respondCreated($userRepository->transform($user));
    }

    /**
     * @Route("/login", methods="POST")
     */
    public function login(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, JWTTokenManagerInterface $JWTManager) {
        $body = json_decode($request->getContent(), true);

        $username = $body['username'];
        $password = $body['password'];

        // $user = $userRepository->findOneBy( array('username' => $username ));
        $user = $em->getRepository(User::class)->findOneBy( array('username' => $username ));
        $currentpwd = $encoder->isPasswordValid($user, $password);
        if ($user != null) {
            if ($currentpwd) {
                return new JsonResponse(['id' => $user->getId(),
                    'username' => $user->getUsername(),
                    'password' => $user->getPassword(),
                    'token' => $JWTManager->create($user)
                ]);
            }
            return $this->setStatusCode(400)->respondWithErrors(sprintf('Wrong Password.'));
        }
        return $this->respondNotFound();
    }

    /**
     * @Route("/users/{id}", methods="PUT")
     */
    public function edit($id, Request $request, UserRepository $userRepository, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder) {
        $body = json_decode($request->getContent(), true);

        $user = $userRepository->find($id);

        $user->setUsername($body['username']);
        $user->setPassword($encoder->encodePassword($user, $body['password']));

        // persist
        $em->persist($user);
        $em->flush();

//        return $this->respond($user);
        return new JsonResponse(['id' => $user->getId(),
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
        ]);
    }

    /**
     * @Route("/users/{id}", methods="DELETE")
     */
    public function delete($id, UserRepository $userRepository, EntityManagerInterface $em) {
        $user = $userRepository->find($id);

        // persist
        $em->remove($user);
        $em->flush();

        return $this->respondWithSuccess(sprintf('User %s successfully deleted', $user->getUsername()));

    }
}
