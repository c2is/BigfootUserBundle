<?php

namespace Bigfoot\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
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
class SecurityController extends BaseController
{
    /**
     * @Route("/login", name="admin_login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
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
    public function loginCheckAction() {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    /**
     * @Route("/logout", name="admin_logout")
     */
    public function logoutAction() {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }

    /**
     * Forgot password
     *
     * @Route("/forgot-password", name="admin_forgot_password")
     * @Template()
     */
    public function forgotPasswordAction(Request $request)
    {
        $form = $this->createForm('admin_forgot_password');

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $user = $form->get('email')->getData();

                if ($user->isPasswordRequestNonExpired($this->container->getParameter('gmu_user.resetting.token_ttl'))) {
                    $this->addFlash('error', $this->getTranslator()->trans('forgot_password.form.errors.request_sent'));

                    return $this->redirect($this->generateUrl('forgot_password'));
                }

                $this->getUserManager()->generateToken($user);

                return $this->redirect($this->generateUrl('forgot_password'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }
}
