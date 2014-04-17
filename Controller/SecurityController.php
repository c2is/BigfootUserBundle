<?php

namespace Bigfoot\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\EventDispatcher\GenericEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

use Bigfoot\Bundle\CoreBundle\Controller\BaseController;
use Bigfoot\Bundle\UserBundle\Form\Model\ForgotPasswordModel;
use Bigfoot\Bundle\UserBundle\Form\Model\ResetPasswordModel;
use Bigfoot\Bundle\UserBundle\Event\UserEvent;

/**
 * BigfootUser controller.
 *
 * @Cache(maxage="0", smaxage="0", public="false")
 */
class SecurityController extends BaseController
{
    /**
     * @Route("/login", name="bigfoot_login")
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        $error = ($error instanceof BadCredentialsException) ? $this->getTranslator()->trans('form.bigfoot_login.exception.bad_credentials') : '';
        $form  = $this->createForm('bigfoot_login', array('username' => $session->get(SecurityContext::LAST_USERNAME)));

        return $this->render(
            $this->getThemeBundle().':user:login.html.twig',
            array(
                'form'  => $form->createView(),
                'error' => $error
            )
        );
    }

    /**
     * @Route("/login_check", name="bigfoot_login_check")
     */
    public function loginCheckAction() {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    /**
     * @Route("/logout", name="bigfoot_logout")
     */
    public function logoutAction() {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }

    /**
     * Forgot password
     *
     * @Route("/forgot-password", name="bigfoot_forgot_password")
     */
    public function forgotPasswordAction(Request $request)
    {
        $form = $this->createForm('bigfoot_forgot_password', new ForgotPasswordModel());

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $user = $form->get('email')->getData();

                if ($user->isPasswordRequestNonExpired($this->container->getParameter('bigfoot_user.resetting.token_ttl'))) {
                    return $this->renderAjax(false, $this->getTranslator()->trans("form.bigfoot_forgot_password.children.email.error.already_sent"));
                }

                $token = $this->getUserManager()->generateToken($user);

                if ($request->isXmlHttpRequest()) {
                    return $this->renderAjax($token['status'], $token['message']);
                } else {
                    return $this->redirect($this->generateUrl('forgot_password'));
                }
            } else {
                if ($request->isXmlHttpRequest()) {
                    return $this->renderAjax(false, $this->getTranslator()->trans("form.bigfoot_forgot_password.children.email.error.non_existing"));
                }
            }
        }

        return $this->render(
            $this->getThemeBundle().':user:forgot_password.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Reset password
     *
     * @Route("/reset-password/{confirmationToken}", name="bigfoot_reset_password")
     */
    public function resetPasswordAction(Request $request, $confirmationToken)
    {
        $user     = $this->getRepository('BigfootUserBundle:User')->findOneByConfirmationToken($confirmationToken);
        $tokenTtl = $this->container->getParameter('bigfoot_user.resetting.token_ttl');

        if (!$user || !$user->isPasswordRequestNonExpired($tokenTtl)) {
            return $this->redirect($this->generateUrl('bigfoot_login'));
        }

        $form = $this->createForm('bigfoot_reset_password', new ResetPasswordModel());

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $user->setPlainPassword($data->plainPassword);

                $this->getEventDispatcher()->dispatch(UserEvent::RESET_PASSWORD, new GenericEvent($user));

                $this->addFlash('success', $this->getTranslator()->trans("form.bigfoot_reset_password.success"));

                return $this->redirect($this->generateUrl('bigfoot_home'));
            }
        }

        return $this->render(
            $this->getThemeBundle().':user:reset_password.html.twig',
            array(
                'form'              => $form->createView(),
                'confirmationToken' => $confirmationToken,
            )
        );
    }
}