<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{

//Implementation strategie d'acces (groups) selon role utilisateur
//nom l'entie filtre
    public function show(): ?JsonResponse
    {
        return $this->json([]);
    }
}
