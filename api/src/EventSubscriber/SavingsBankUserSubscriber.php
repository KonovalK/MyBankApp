<?php

// src/EventSubscriber/SavingsBankUserSubscriber.php
namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\SavingsBank;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class SavingsBankUserSubscriber implements EventSubscriberInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['setUserForSavingsBank', EventPriorities::PRE_WRITE],
        ];
    }

    public function setUserForSavingsBank(ViewEvent $event): void
    {
        $savingsBank = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$savingsBank instanceof SavingsBank || !in_array($method, ['POST', 'PUT'])) {
            return;
        }

        if (null === $savingsBank->getUser()) {
            $user = $this->security->getUser();
            $savingsBank->setUser($user);
        }
    }
}



