<?php

namespace Bigfoot\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Bigfoot\Bundle\CoreBundle\Theme\Menu\Item;
use Bigfoot\Bundle\UserBundle\Entity\BigfootRole;
use Bigfoot\Bundle\UserBundle\Form\BigfootRoleType;

/**
 * BigfootRole controller.
 *
 * @Route("/admin/role")
 */
class BigfootRoleController extends Controller
{

    /**
     * Lists all BigfootRole entities.
     *
     * @Route("/", name="admin_role")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BigfootUserBundle:BigfootRole')->findAll();

        $this->container->get('bigfoot.theme')['page_content']['globalActions']->addItem(new Item('crud_add', 'Add a role', 'admin_role_new'));

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new BigfootRole entity.
     *
     * @Route("/", name="admin_role_create")
     * @Method("POST")
     * @Template("BigfootUserBundle:BigfootRole:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new BigfootRole();
        $form = $this->createForm(new BigfootRoleType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_role_edit', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new BigfootRole entity.
     *
     * @Route("/new", name="admin_role_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new BigfootRole();
        $form   = $this->createForm(new BigfootRoleType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing BigfootRole entity.
     *
     * @Route("/{id}/edit", name="admin_role_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BigfootUserBundle:BigfootRole')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BigfootRole entity.');
        }

        $editForm = $this->createForm(new BigfootRoleType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing BigfootRole entity.
     *
     * @Route("/{id}", name="admin_role_update")
     * @Method("PUT")
     * @Template("BigfootUserBundle:BigfootRole:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BigfootUserBundle:BigfootRole')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BigfootRole entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new BigfootRoleType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_role_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a BigfootRole entity.
     *
     * @Route("/{id}", name="admin_role_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BigfootUserBundle:BigfootRole')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find BigfootRole entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_role'));
    }

    /**
     * Creates a form to delete a BigfootRole entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
