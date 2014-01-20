<?php

namespace Bigfoot\Bundle\UserBundle\Listener;

use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Doctrine\ORM\EntityManager;

use Bigfoot\Bundle\UserBundle\Entity\BigfootUser;

/**
 * User Listener
 */
class UserListener implements EventSubscriberInterface
{
    private $userManager;

    /**
     * Construct UserListener
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * Get subscribed events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
            UserEvents::RESET_PASSWORD        => 'onResetPassword',
        );
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof BigfootUser) {
            $user->setLastLogin(new \DateTime());
            $this->userManager->updateUser($user);
        }
    }

    /**
     * Reset password
     */
    public function onResetPassword(GenericEvent $event)
    {
        $user = $event->getSubject();

        $user->setConfirmationToken(null);
        $user->setPasswordRequestedAt(null);

        $this->userManager->updateUser($user);
    }
}
