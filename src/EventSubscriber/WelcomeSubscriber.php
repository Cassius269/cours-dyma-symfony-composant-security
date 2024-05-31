<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;

class WelcomeSubscriber implements EventSubscriberInterface
{
    public function onAuthenticationSuccessEvent(AuthenticationSuccessEvent $event): void
    {
        // ...
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AuthenticationSuccessEvent::class => 'onAuthenticationSuccessEvent',
        ];
    }
}
