<?php

namespace Bigfoot\Bundle\UserBundle\Controller;

use Bigfoot\Bundle\UserBundle\Form\AccountType;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContext;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

/**
 * BigfootUser controller.
 *
 * @Cache(maxage="0", smaxage="0", public="false")
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
            throw new NotFoundHttpException('No user account found');
        }

        $editForm = $this->container->get('form.factory')->create(new AccountType($this->container->get('security.encoder_factory')), $user);

        return array(
            'form'                  => $editForm->createView(),
            'form_method'           => 'PUT',
            'form_action'           => $this->container->get('router')->generate('user_account_edit'),
            'form_title'            => 'My account',
            'isAjax'                => $this->container->get('request')->isXmlHttpRequest(),
            'breadcrumbs'       => array(
                array(
                    'label' => 'My account'
                ),
            ),
        );
    }

    /**
     * Edits an existing BigfootUser entity.
     *
     * @Route("/account/edit", name="user_account_edit")
     * @Method("PUT")
     * @Template("BigfootUserBundle::account.html.twig")
     */
    public function updateAction(Request $request)
    {
        $em = $this->container->get('doctrine')->getManager();

        $entity = $this->container->get('security.context')->getToken()->getUser();

        if (!$entity) {
            throw new NotFoundHttpException('Unable to find BigfootUser entity.');
        }

        $editForm = $this->container->get('form.factory')->create(new AccountType($this->container->get('security.encoder_factory')), $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->container->get('session')->getFlashBag()->add(
                'success',
                $this->container->get('templating')->render('BigfootCoreBundle:includes:flash.html.twig', array(
                    'icon' => 'ok',
                    'heading' => 'Success!',
                    'message' => 'Your account has been updated.',
                ))
            );

            return new RedirectResponse($this->container->get('router')->generate('user_account'));
        }

        return array(
            'user'      => $entity,
            'edit_form' => $editForm->createView(),
        );
    }
}
