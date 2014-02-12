<?php

namespace Bigfoot\Bundle\UserBundle\Mailer;

use Doctrine\ORM\EntityManager;

use Bigfoot\Bundle\CoreBundle\Mailer\AbstractMailer;
use Bigfoot\Bundle\UserBundle\Entity\User;

class UserMailer extends AbstractMailer
{
    public function sendForgotPasswordMail(User $user, $token)
    {
        $body = $this->templating->render(
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
