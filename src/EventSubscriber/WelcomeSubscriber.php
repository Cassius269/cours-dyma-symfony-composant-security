<?php

namespace App\EventSubscriber;

use App\Event\NewUserEvent;
use Symfony\Component\Mime\Email;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WelcomeSubscriber implements EventSubscriberInterface
{
    public ?MailerInterface $mailer = null;

    public function __construct(?MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function OnNewUserEvent(NewUserEvent $event)
    {
        $user = $event->getUser();
        $mailAdress = $user->getEmail();

        $email = new Email();
        $email->from('Service Client<fahamygaston@gmail.com>')
            ->to($mailAdress)
            ->subject('Inscription réussi')
            ->text('Bienvenu ' . $user->getName());

        $this->mailer->send($email);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            NewUserEvent::class => 'OnNewUserEvent'
        ];
    }
}
