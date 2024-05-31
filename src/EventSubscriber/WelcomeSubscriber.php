<?php

namespace App\EventSubscriber;

use NewUserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class WelcomeSubscriber implements EventSubscriberInterface
{
    public ?Filesystem $fs = null;

    public function __construct(Filesystem $fileSystem)
    {
        $this->fs = $fileSystem;
    }

    public function onLoginSuccessEvent(LoginSuccessEvent $event): void
    {
        // Ajout d'un message Flash à la connexion d'utilisateur
        $request = $event->getRequest();
        $username = $event->getPassport()->getUser()->getName();
        $request->getSession()->getFlashBag()->add('success', 'welcome ' . $username);
    }

    public function onLogoutSuccess(LogoutEvent $event)
    {
        // dd($event);
        $request = $event->getRequest();
        $tocken = $event->getToken();
        $username = $tocken->getUser()->getName();

        $this->fs->mkdir('Events');
        $this->fs->touch('Events/disconnedUser.txt');
        $this->fs->appendTofile('Events/disconnedUser.txt', $username .  "s'est déconnecté(e)" . PHP_EOL);
        $request->getSession()->getFlashBag()->add('success', 'Vous êtes déconnecté(e)');
    }

    public function OnNewUserEvent(NewUserEvent $event)
    {
        $this->fs->appendToFile('Events/log.txt', $event->getEmail() . " vient de s'inscrire");
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => ['onLoginSuccessEvent', 150],
            LogoutEvent::class => 'onLogoutSuccess',
            NewUserEvent::class => 'OnNewUserEvent'
        ];
    }
}
