<?php

namespace App\Controller;

use App\Entity\Token;
use App\Entity\User;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Helpers\PasswordHelper;
use App\Helpers\StringHelper;
use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    #[Route('/user/generate_admin', name: 'app_user', methods: ['POST'])]
    public function generate_admin(ManagerRegistry $doctrine, UserRepository $userRepository): JsonResponse
    {

        // create admin user
        $adminUser = new User();
        $adminUser->setFirstName("Ata");
        $adminUser->setLastName("Zangene");
        $adminUser->setEmail("convertersoft@gmail.com");
        $adminUser->setRole(UserRole::$ADMIN);
        $adminUser->setStatus(UserStatus::$ACTIVE);

        // Hash a plain password
        $plainPassword = $this->getParameter('app.admin_password');
        $hashedPassword = PasswordHelper::hash($plainPassword);
        $adminUser->setPassword($hashedPassword);

        // generate admin user
        try {
            $userRepository->generateAdminUser($adminUser);
        } catch (\Exception $exception) {
            return $this->json([
                'error' => $exception->getMessage(),
            ]);
        }

        return $this->json([
            'message' => 'Admin user generated successfully',
        ]);
    }


    #[Route('/user/add_user', name: 'add_user', methods: ['POST'])]
    public function add_user(ManagerRegistry $doctrine, UserRepository $userRepository): JsonResponse
    {

        // get parameters
        $request = Request::createFromGlobals();
        $request->getPathInfo();
        $email = $request->request->get('email');
        $firstName = $request->request->get('first_name');
        $lastName = $request->request->get('last_name');
        $plainPassword = $request->request->get('password');


        // create admin user
        $user = new User();
        $user->setEmail($email);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setRole(UserRole::$NORMAL);
        $user->setStatus(UserStatus::$ACTIVE);

        // Hash a plain password;
        $hashedPassword = PasswordHelper::hash($plainPassword);
        $user->setPassword($hashedPassword);

        // add user to database
        try {
            $userRepository->add($user, true);
        } catch (\Exception $exception) {
            return $this->json([
                'error' => $exception->getMessage(),
            ]);
        }


        return $this->json([
            'message' => 'User created successfully',
            'userId' => $user->getId()
        ]);
    }


    #[Route('/user/generate_token', name: 'generate_token', methods: ['POST'])]
    public function generate_token(ManagerRegistry $doctrine, UserRepository $userRepository, TokenRepository $tokenRepository): JsonResponse
    {

        // get parameters
        $request = Request::createFromGlobals();
        $request->getPathInfo();
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        try {

            // generate a token
            $tokenValidPeriod = $this->getParameter('app.token_valid_period');
            $generatedToken = $tokenRepository->generateToken($email, $password, $tokenValidPeriod);

            return $this->json([
                'message' => 'Token generated successfully',
                'user_id' => $generatedToken->getUserId(),
                'token' => $generatedToken->getToken(),
            ]);

        } catch (\Exception $exception) {
            return $this->json([
                'error' => $exception->getMessage(),
            ]);
        }


    }


}
