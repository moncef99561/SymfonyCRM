<?php

namespace App\Event\LoginEvent;

use App\Entity\Utilisateur;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSubscriber implements EventSubscriberInterface
{
    private ?FlashBagInterface $flashBag;

    public function __construct(RequestStack $requestStack)
    {
        /** @var \Symfony\Component\HttpFoundation\Session\Session $session */
        $session = $requestStack->getSession();

        $this->flashBag = $session?->getFlashBag();

    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();

        if ($user instanceof Utilisateur && $this->flashBag) {
            $this->flashBag->add('success', 'Bienvenue ' . $user->getNom() . ' !');
        }
    }
}
