<?php

namespace Bigfoot\Bundle\UserBundle\Mailer;

use Doctrine\ORM\EntityManager;

use Bigfoot\Bundle\CoreBundle\Mailer\AbstractMailer;
use Bigfoot\Bundle\UserBundle\Entity\BigfootUser;

class UserMailer extends AbstractMailer
{
    public function sendForgotPasswordMail(BigfootUser $user, $token)
    {
        $body = $this->templating->render(
            'BigfootUserBundle:Mail:forgotPassword.html.twig',
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
