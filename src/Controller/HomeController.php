<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(PostRepository $postRepository): Response
    {

        $posts = $postRepository->findAll();

if(!$posts){
    throw $this->createNotFoundException("Aucun article trouvé");
}

        return $this->render('home/index.html.twig', [
            'posts' => $posts,
        ]);
    }
}
