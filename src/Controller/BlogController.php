<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: '/blog', name: 'blog')]
class BlogController extends AbstractController
{
    #[Route('/edit/{id}', name: 'form')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(Post $post, EntityManagerInterface $em, Request $request, Security $security): Response
    {
   
      //  dd($post);
      //  $user = $security->getUser();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->addFlash('success', 'Votre post a été créé');
    
                return $this->redirectToRoute('home');
            }
            
        return $this->render('blog/index.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/edit', name: 'createpost')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function createpost( EntityManagerInterface $em, Request $request, Security $security): Response
    {
   
        $post = new Post();
        $user = $security->getUser();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $post->setCreatedAt(new \DateTimeImmutable());
                $post->setUser($user);
                $em->persist($post);
                $em->flush();
                $this->addFlash('success', 'Votre post a été créé');
    
                return $this->redirectToRoute('home');
            }

        return $this->render('blog/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
