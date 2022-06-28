<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseTokenClass extends AbstractController
{
    protected ?User $loggedInUser;

    public function __construct(TokenRepository $tokenRepository, UserRepository $userRepository)
    {

        $request = Request::createFromGlobals();
        $request->getPathInfo();

        // retrieves $_GET and $_POST variables respectively
        $authUserId = $request->request->get('auth_user_id');
        $authUserToken = $request->request->get('auth_user_token');

        // get model from database
        $tokenModel = $tokenRepository->findOneBy([
            "userId" => $authUserId,
            "token" => $authUserToken
        ]);

        if (!$tokenModel) {
            $result = new \stdClass();
            $result->message = 'Invalid user/token provided';
            $response = new Response(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            echo $response;
            die();
        }

        // user has valid token, try to load the user
        $this->loggedInUser = $userRepository->findOneBy([
            "id" => $authUserId
        ]);
    }
}