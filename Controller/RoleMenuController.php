<?php

namespace Bigfoot\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Yaml\Yaml;

use Doctrine\Common\Collections\ArrayCollection;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

use Bigfoot\Bundle\CoreBundle\Controller\BaseController;
use Bigfoot\Bundle\CoreBundle\Theme\Menu\Item;
use Bigfoot\Bundle\UserBundle\Form\RoleMenuType;
use Bigfoot\Bundle\UserBundle\Entity\RoleMenu;

use Bigfoot\Bundle\UserBundle\Form\Type\RoleMenusType;

/**
 * RoleMenu controller.
 *
 * @Cache(maxage="0", smaxage="0", public="false")
 * @Route("/role/menu")
 */
class RoleMenuController extends BaseController
{
    private function getMenuRoleManager()
    {
        return $this->container->get('bigfoot_user.manager.role_menu');
    }

    /**
     * Lists all Role entities.
     *
     * @Route("/", name="admin_role_menu")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $items = $this->getMenuRoleManager()->getItems();
        $form  = $this->createForm(new RoleMenusType(), array('roleMenus' => new ArrayCollection($items)));

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $datas     = $form->getData();
            $roleMenus = $datas['roleMenus'];

            foreach ($roleMenus as $roleMenu) {
                $this->persistAndFlush($roleMenu);
            }
        }

        return $this->render(
            $this->getThemeBundle().':RoleMenu:index.html.twig',
            array(
            'items' => $items,
            'form'  => $form->createview()
        ));
    }
}
