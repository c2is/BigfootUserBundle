<?php

namespace Bigfoot\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

use Bigfoot\Bundle\CoreBundle\Controller\BaseController;
use Bigfoot\Bundle\UserBundle\Form\Type\BigfootUserType;

/**
 * BigfootUser controller.
 *
 * @Cache(maxage="0", smaxage="0", public="false")
 * @Route("/admin")
 */
class UserController extends BaseController
{
    /**
     * @Route("/account", name="admin_user_account")
     * @Template()
     */
    public function accountAction(Request $request)
    {
        $user = $this->getUser();

        if (!$user) {
            throw new NotFoundHttpException('No user account found');
        }

        $form = $this->createForm('bigfoot_user', $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->persistAndFlush($user);

            $this->addFlash(
                'success',
                $this->render(
                    'BigfootCoreBundle:includes:flash.html.twig',
                    array(
                        'icon'    => 'ok',
                        'heading' => 'Success!',
                        'message' => 'Your account has been updated.',
                    )
                )
            );
        }

        return array(
            'form'        => $form->createView(),
            'form_method' => 'POST',
            'form_action' => $this->generateUrl('admin_user_account'),
            'form_title'  => 'My account',
            'isAjax'      => $this->getRequest()->isXmlHttpRequest(),
            'breadcrumbs' => array(
                array(
                    'label' => 'My account',
                ),
            ),
        );
    }
}
