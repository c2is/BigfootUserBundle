<?php

namespace Bigfoot\Bundle\UserBundle\Mailer;

use Doctrine\ORM\EntityManager;

use Bigfoot\Bundle\CoreBundle\Mailer\AbstractMailer;

class UserMailer extends AbstractMailer
{
    public function test()
    {
         var_dump('lol');die();
    }
}
