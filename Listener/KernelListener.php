<?php

namespace Bigfoot\Bundle\UserBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\User\UserInterface;

class KernelListener
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $locale = $this->container->getParameter('locale');
        if ($requestLocale = $event->getRequest()->get('_locale')) {
            $locale = $requestLocale;
        } elseif ($token = $this->container->get('security.context')->getToken() and $user = $token->getUser() and $user instanceof UserInterface) {
            $locale = $user->getLocale();
        }
        $event->getRequest()->setLocale($locale);
    }

    public function onLateKernelRequest(GetResponseEvent $event)
    {
        $this->container->get('gedmo.listener.translatable')->setTranslatableLocale($event->getRequest()->getLocale());
    }
}