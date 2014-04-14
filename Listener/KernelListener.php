<?php

namespace Bigfoot\Bundle\UserBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Translatable\TranslatableListener;

class KernelListener
{
    protected $securityContext;
    protected $translationListener;

    public function __construct(SecurityContextInterface $securityContext, TranslatableListener $translationListener)
    {
        $this->securityContext     = $securityContext;
        $this->translationListener = $translationListener;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();
        $token   = $this->securityContext->getToken();

        if ($locale = $request->getSession()->get('_locale', false)) {
            $request->setLocale($locale);
        } elseif (!$token) {
            $request->setLocale($token->getUser()->getLocale());
        } else {
            $request->setLocale($request->getPreferredLanguage());
        }
    }

    public function onLateKernelRequest(GetResponseEvent $event)
    {
        $this->translationListener->setTranslatableLocale($event->getRequest()->getLocale());
    }
}
