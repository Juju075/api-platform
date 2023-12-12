<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class customController extends AbstractController
{
    public function __construct()
    {
    }

    public function __invoke(Post $post): Post
    {
        // TODO: Implement __invoke() method.
        // enregistre un nouveau post
    }

    // Validation des donnees @Assert
    // Ajouter un utilisateur uniquement pour les admin @security
    // Exeption @catch
    // Audit enregistrement de log @log
    // Tranformation des donnees @Transfom

}