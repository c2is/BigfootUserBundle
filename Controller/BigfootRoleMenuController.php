<?php

namespace Bigfoot\Bundle\UserBundle\Controller;

use Bigfoot\Bundle\UserBundle\Entity\BigfootRoleMenu;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Bigfoot\Bundle\UserBundle\Form\BigfootRoleMenuType;
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

        $repository = $this->getDoctrine()
            ->getRepository('BigfootUserBundle:BigfootRole');

        $role = $repository->findOneById($id);

        $entity = $em->getRepository('BigfootUserBundle:BigfootRoleMenu')->findOneBy(array("role" => $role->getName()));

        if (!$entity) {
            $entity = new BigfootRoleMenu();
            $entity->setRole($role->getName());
        }

        $editForm = $this->createForm('bigfootrolemenutype', $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
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
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BigfootUserBundle:BigfootRoleMenu')->findOneBy(array("role" => $role));

        if (!$entity) {
            $entity = new BigfootRoleMenu();
            $entity->setRole($role);
        }

        $editForm = $this->createForm('bigfootrolemenutype', $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_role_menu'));
        }

        return array(
            'entity'        => $entity,
            'edit_form'     => $editForm->createView(),
        );
    }
}
