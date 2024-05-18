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
    #[IsGranted('ROLE_USER')]
    public function editpost(Post $post = null, EntityManagerInterface $em, Request $request, Security $security): Response
    {

        if (!$post) { // si aucun post correspondant trouvé
            throw $this->createNotFoundException('Aucun post trouvé à cet id');
        }

        $user = $security->getUser();
        // dd($user);

        if ($post->getUser() == $user) {
            $form = $this->createForm(PostType::class, $post);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->flush();
                $this->addFlash('success', 'Votre post a été mis à jour');

                return $this->redirectToRoute('home');
            }
        } else {
            dd('edit pas ok');
            throw $this->createNotFoundException('Vous n\'avez pas un droit de mise à jour sur ce post');
        }


        return $this->render('blog/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit', name: 'createpost')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function createpost(EntityManagerInterface $em, Request $request, Security $security): Response
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
            $em->flush();
            $this->addFlash('success', 'Votre post a été créé');

            return $this->redirectToRoute('home');
        }
        return $this->render('blog/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
