<?php

namespace Bigfoot\Bundle\UserBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * ForgotPassword Model
 */
class ForgotPasswordModel
{
    /**
     * @Assert\NotBlank()
     */
    public $email;
}
