<?php
namespace App\EventListener;

use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginListener {

    public function __invoke(LoginSuccessEvent $event){
        $request= $event->getRequest();

        dump($request->getSession()->getUser());
        $userName= $security->getUser();
        (($request->getSession())->getFlashBag())->add('success','Bon retour parmi nous'.$userName);
    }
}