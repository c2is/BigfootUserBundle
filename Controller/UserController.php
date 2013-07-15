<?php

namespace Bigfoot\Bundle\UserBundle\Controller;

use Bigfoot\Bundle\UserBundle\Form\AccountType;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * BigfootUser controller.
 *
 * @Route("/admin/user")
 */
class UserController extends ContainerAware
{
    /**
     * @Route("/account", name="user_account")
     * @Template("BigfootUserBundle::account.html.twig")
     */
    public function accountAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        if (!$user) {
            throw $this->createNotFoundException('No user account found');
        }

        $editForm = $this->container->get('form.factory')->create(new AccountType($this->container->get('security.encoder_factory')), $user);

        return array(
            'user'      => $user,
            'edit_form' => $editForm->createView(),
        );
    }
}