<?php

namespace Bigfoot\Bundle\UserBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * ResetPassword Model
 */
class ResetPasswordModel
{
    /**
     * @Assert\NotBlank()
     */
    public $plainPassword;
}
