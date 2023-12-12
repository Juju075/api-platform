<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route(path: '/api/login', name: 'api_login', methods: ['POST'])]
    public function login(): ?JsonResponse
    {

        $user = $this->getUser();
        return $this->json([
            'username'=>$user->getEmail(),
            'role'=>$user->getRoles()
        ]);


        //recuperer le json
        //awaiting body username et password
        //data security
        //UserRepository dql verification
        //Success handler
        //Failure handler

    }
    //handel token
    public function SuccessHandler()
    {

    }
    //status  credential error
    public function FailureHandler()
    {

    }
}
