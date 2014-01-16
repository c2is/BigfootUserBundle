<?php

namespace Bigfoot\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

use Bigfoot\Bundle\CoreBundle\Controller\BaseController;

/**
 * BigfootUser controller.
 *
 * @Cache(maxage="0", smaxage="0", public="false")
 * @Route("/admin")
 */
class LoginController extends BaseController
{
    /**
     * @Route("/login", name="admin_login")
     * @Template("BigfootUserBundle::login.html.twig")
     */
    public function loginAction()
    {
        $request = $this->container->get('request');
        $session = $request->getSession();

        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        );
    }

    /**
     * @Route("/login_check", name="admin_login_check")
     */
    public function loginCheckAction() {die;
        return null;
    }

    /**
     * @Route("/logout", name="admin_logout")
     */
    public function logoutAction() {
        return null;
    }
}
