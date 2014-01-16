<?php

namespace Bigfoot\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Yaml\Yaml;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

use Bigfoot\Bundle\CoreBundle\Controller\CrudController;
use Bigfoot\Bundle\CoreBundle\Theme\Menu\Item;
use Bigfoot\Bundle\UserBundle\Form\BigfootRoleMenuType;
use Bigfoot\Bundle\UserBundle\Entity\BigfootRoleMenu;

/**
 * BigfootRoleMenu controller.
 *
 * @Cache(maxage="0", smaxage="0", public="false")
 * @Route("/admin/role/menu")
 */
class BigfootRoleMenuController extends CrudController
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'admin_role_menu';
    }

    /**
     * @return string
     */
    protected function getEntity()
    {
        return 'BigfootUserBundle:BigfootRoleMenu';
    }

    protected function getEntityLabel()
    {
        return 'User role menu';
    }

    public function getFields()
    {
        return array(
            'name' => 'Name',
            'label' => 'Label',
        );
    }

    /**
     * Lists all BigfootRole entities.
     *
     * @Route("/", name="admin_role_menu")
     * @Method("GET")
     * @Template("BigfootCoreBundle:crud:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->container->get('doctrine')->getManager();

        $entities = $em->getRepository('BigfootUserBundle:BigfootRole')->findAll();

        if (method_exists($this, 'newAction')) {
            $theme = $this->container->get('bigfoot.theme');
            $theme['page_content']['globalActions']->addItem(new Item('crud_add', $this->getAddLabel(), $this->getRouteNameForAction('new'), array(), array(), 'file'));
        }

        return array(
            'list_items'        => $entities,
            'list_title'        => $this->getEntityLabelPlural(),
            'list_fields'       => $this->getFields(),
            'breadcrumbs'       => array(
                array(
                    'url'   => $this->container->get('router')->generate($this->getRouteNameForAction('index')),
                    'label' => $this->getEntityLabelPlural()
                ),
            ),
            'actions'           => array(
                array(
                    'href'  => $this->container->get('router')->generate($this->getRouteNameForAction('edit'), array('id' => '__ID__')),
                    'icon'  => 'pencil',
                )
            )
        );
    }

    /**
     * Displays a form to edit an existing BigfootRole entity.
     *
     * @Route("/{id}/edit", name="admin_role_menu_edit")
     * @Method("GET")
     * @Template("BigfootCoreBundle:crud:edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->container->get('doctrine')->getManager();

        $repository = $this->container->get('doctrine')
            ->getRepository('BigfootUserBundle:BigfootRole');

        $role = $repository->findOneById($id);

        $entity = $em->getRepository('BigfootUserBundle:BigfootRoleMenu')->findOneBy(array('role' => $role->getName()));

        if (!$entity) {
            $entity = new BigfootRoleMenu();
            $entity->setRole($role->getName());
            $em->persist($entity);
            $em->flush();
        }

        $editForm = $this->container->get('form.factory')->create('bigfootrolemenutype', $entity);

        return array(
            'form'                  => $editForm->createView(),
            'form_method'           => 'PUT',
            'form_action'           => $this->container->get('router')->generate($this->getRouteNameForAction('update'), array('role' => $entity->getRole())),
            'form_cancel_route'     => $this->getRouteNameForAction('index'),
            'form_title'            => sprintf('%s edit', $this->getEntityLabel()),
            'isAjax'                => $this->container->get('request')->isXmlHttpRequest(),
            'breadcrumbs'       => array(
                array(
                    'url'   => $this->container->get('router')->generate($this->getRouteNameForAction('index')),
                    'label' => $this->getEntityLabelPlural()
                ),
                array(
                    'url'   => $this->container->get('router')->generate($this->getRouteNameForAction('edit'), array('id' => $entity->getId())),
                    'label' => sprintf('%s edit', $this->getEntityLabel())
                ),
            ),
        );
    }

    /**
     * Edits an existing BigfootUser entity.
     *
     * @Route("/{role}", name="admin_role_menu_update")
     * @Method("PUT")
     * @Template("BigfootUserBundle:BigfootRoleMenu:edit.html.twig")
     */
    public function updateAction(Request $request, $role)
    {
        $em = $this->container->get('doctrine')->getManager();

        $entity = $em->getRepository('BigfootUserBundle:BigfootRoleMenu')->findOneBy(array("role" => $role));

        if (!$entity) {
            $entity = new BigfootRoleMenu();
            $entity->setRole($role);
        }

        $editForm = $this->container->get('form.factory')->create('bigfootrolemenutype', $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return new RedirectResponse($this->container->get('router')->generate('admin_role_menu'));
        }

        return array(
            'entity'        => $entity,
            'edit_form'     => $editForm->createView(),
        );
    }
}

