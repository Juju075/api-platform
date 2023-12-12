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
    }

}