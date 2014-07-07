<?php

namespace Bigfoot\Bundle\UserBundle\Mailer;

use Doctrine\ORM\EntityManager;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\GenericEvent;

use Bigfoot\Bundle\CoreBundle\Mailer\AbstractMailer;
use Bigfoot\Bundle\UserBundle\Entity\User;
use Bigfoot\Bundle\UserBundle\Event\UserEvent;

class UserMailer extends AbstractMailer
{
    private $eventDispatcher;

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function sendForgotPasswordMail(User $user, $token)
    {
        $body = $this->getTemplating()->render(
            'BigfootUserBundle:Mail:forgot_password.html.twig',
            array(
                'token' => $token,
            )
        );

        $this->sendMail(
            'Forgot password',
            $user->getEmail(),
            $body
        );
    }

    /**
     * sendCreatePasswordMail
     *
     * @param  User   $user
     * @param  string $token
     *
     * @return boolean
     */
    public function sendCreatePasswordMail(User $user, $token)
    {
        $event = new GenericEvent(
            array(
                'user'  => $user,
                'token' => $token
            )
        );

        $return = $this->eventDispatcher->dispatch(UserEvent::CREATE_PASSWORD, $event);

        if (!$event->isPropagationStopped()) {
            $body = $this->getTemplating()->render(
                'BigfootUserBundle:Mail:forgot_password.html.twig',
                array(
                    'token' => $token,
                )
            );

            $this->sendMail(
                'Forgot password',
                $user->getEmail(),
                $body
            );
        }
    }
}
