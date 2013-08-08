<?php

namespace Bigfoot\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Bigfoot\Bundle\UserBundle\Entity\BigfootRole;
use Bigfoot\Bundle\UserBundle\Form\BigfootRoleMenuType;
use Symfony\Component\Yaml\Dumper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Bigfoot\Bundle\CoreBundle\Theme\Menu\Item;
use Symfony\Component\Yaml\Yaml;

/**
 * BigfootRoleMenu controller.
 *
 * @Route("/admin/role/menu")
 */
class BigfootRoleMenuController extends Controller
{

    /**
     * Lists all BigfootRole entities.
     *
     * @Route("/", name="admin_role_menu")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BigfootUserBundle:BigfootRole')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Displays a form to edit an existing BigfootRole entity.
     *
     * @Route("/{id}/edit", name="admin_role_menu_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $this->container->get('bigfoot.theme');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BigfootUserBundle:BigfootRole')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BigfootRole entity.');
        }

        $editForm = $this->createFormMenu($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
     * Edits an existing BigfootUser entity.
     *
     * @Route("/{id}", name="admin_role_menu_update")
     * @Method("PUT")
     * @Template("BigfootUserBundle:BigfootRoleMenu:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $this->container->get('bigfoot.theme');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BigfootUserBundle:BigfootRole')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BigfootRole entity.');
        }

        $editForm = $this->createFormMenu($entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $userConfig = $this->container->getParameter('bigfoot_user');
            $data = $editForm->getData();
            $newConfig = array();
            $newMenuConfig = array();
            $newConfig["bigfoot_user"]["menu_security"] = $userConfig["menu_security"];

            foreach ($userConfig["menu_security"] as $menuConfig => $role) {
                foreach ($data["menu"] as $menuData) {
                    if (!in_array($entity->getName(),$role) && $menuConfig == $menuData) {
                        $newMenuConfig[$menuData] = array_merge($userConfig["menu_security"][$menuData],array($entity->getName()));
                        $newConfig["bigfoot_user"]["menu_security"] = array_merge($newConfig["bigfoot_user"]["menu_security"],$newMenuConfig);
                    }
                    elseif (!array_key_exists($menuData, $newConfig["bigfoot_user"]["menu_security"])) {
                        $newMenuConfig[$menuData][] = $entity->getName();
                        $newConfig["bigfoot_user"]["menu_security"] = array_merge($newConfig["bigfoot_user"]["menu_security"],$newMenuConfig);
                    }
                    unset($newMenuConfig);
                }
                if (!in_array($menuConfig, $data["menu"]) && in_array($entity->getName(),$role)) {
                    $indexRole = array_search($role, $userConfig["menu_security"][$menuConfig]);
                    unset($userConfig["menu_security"][$menuConfig][$indexRole]);
                    $userConfig["menu_security"][$menuConfig] = array_values($userConfig["menu_security"][$menuConfig]);
                    if(count($userConfig["menu_security"][$menuConfig]) == 0) {
                        unset($userConfig["menu_security"][$menuConfig]);
                    }
                    $newConfig["bigfoot_user"]["menu_security"] = $userConfig["menu_security"];
                }
            }
            if (count($newConfig) > 0) {
                $yaml = Yaml::dump($newConfig, 4);

                file_put_contents($this->get('kernel')->getRootDir().'/config/menu_security.yml', $yaml);
            }

            return $this->redirect($this->generateUrl('admin_role_menu'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
     * @param BigfootRole $bigfootRole
     * @return \Symfony\Component\Form\Form
     */
    public function createFormMenu(BigfootRole $bigfootRole)
    {
        $itemsTemp = array();
        $items = array();
        $menus = array();
        $userConfig = $this->container->getParameter('bigfoot_user');

        foreach ($userConfig["menu_security"] as $menu => $roles) {
            foreach ($roles as $role) {
                if ($role == $bigfootRole->getName()) {
                    $menus[] = $menu;
                }
            }
        }

        $menuFactory = $this->get("bigfoot.menu_factory");
        foreach ($menuFactory->getMenus() as $menu) {
            $itemsTemp = array_merge($itemsTemp,$menu->getItems());
        }
        foreach ($itemsTemp as $item) {
            $items[$item->getName()] = $item;
        }

        $editForm = $this->createFormBuilder(array("menu" => $menus))
            ->add('menu', 'choice', array("choices" => $items, "multiple" => true))
            ->getForm()
        ;

        return $editForm;
    }
}
