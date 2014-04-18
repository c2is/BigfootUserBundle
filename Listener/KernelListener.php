<?php

namespace Bigfoot\Bundle\UserBundle\Listener;

use Bigfoot\Bundle\UserBundle\Model\User;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Gedmo\Translatable\TranslatableListener;

/**
 * Class KernelListener
 * @package Bigfoot\Bundle\UserBundle\Listener
 */
class KernelListener
{
    /** @var \Symfony\Component\Security\Core\SecurityContextInterface */
    protected $securityContext;

    /**
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();
        $token   = $this->securityContext->getToken();

        if ($token and $user = $token->getUser() and $user instanceof User) {
            $request->setLocale($user->getLocale());
        }
    }
}
