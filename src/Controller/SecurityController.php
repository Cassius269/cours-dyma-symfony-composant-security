<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Event\NewUserEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route('/inscription', name: 'inscription')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, EventDispatcherInterface $eventDispatcher): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($userPasswordHasher->hashPassword($user, $user->getPassword()));
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setRoles(["ROLE_USER"]);
            $entityManager->persist($user);
            $eventDispatcher->dispatch(new NewUserEvent($user));

            $entityManager->flush();

            $this->addFlash('success', 'Vous êtes inscrit(e) maintenant');
            return $this->redirectToRoute('home');
        }

        return $this->render('security/inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/connexion', name: 'connexion')]
    public function connect(AuthenticationUtils $authenticationUtils, RequestStack $requeststack): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $username = $authenticationUtils->getLastUsername();

        $session = $requeststack->getSession();
        $session->set('username', $username);


        if ($error) {
            $this->addFlash("erreur", 'Une erreur s\'est produite');
        }

        return $this->render('security/connexion.html.twig', [
            "error" => $error,
            "username" => $username
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout()
    {
    }
}
