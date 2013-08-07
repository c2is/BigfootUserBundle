<?php

namespace Bigfoot\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Bigfoot\Bundle\CoreBundle\Crud\CrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * BigfootRole controller.
 *
 * @Route("/admin/role")
 */
class BigfootRoleController extends CrudController
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'admin_role';
    }

    /**
     * @return string
     */
    protected function getEntity()
    {
        return 'BigfootUserBundle:BigfootRole';
    }

    protected function getFields()
    {
        return array('id' => 'ID', 'name' => 'Name', 'label' => 'Label');
    }
    /**
     * Lists all BigfootRole entities.
     *
     * @Route("/", name="admin_role")
     * @Method("GET")
     * @Template("BigfootCoreBundle:crud:index.html.twig")
     */
    public function indexAction()
    {
        return $this->doIndex();
    }
    /**
     * Creates a new BigfootRole entity.
     *
     * @Route("/", name="admin_role_create")
     * @Method("POST")
     * @Template("BigfootCoreBundle:crud:new.html.twig")
     */
    public function createAction(Request $request)
    {

        return $this->doCreate($request);
    }

    /**
     * Displays a form to create a new BigfootRole entity.
     *
     * @Route("/new", name="admin_role_new")
     * @Method("GET")
     * @Template("BigfootCoreBundle:crud:new.html.twig")
     */
    public function newAction()
    {

        return $this->doNew();
    }

    /**
     * Displays a form to edit an existing BigfootRole entity.
     *
     * @Route("/{id}/edit", name="admin_role_edit")
     * @Method("GET")
     * @Template("BigfootCoreBundle:crud:edit.html.twig")
     */
    public function editAction($id)
    {

        return $this->doEdit($id);
    }

    /**
     * Edits an existing BigfootRole entity.
     *
     * @Route("/{id}", name="admin_role_update")
     * @Method("PUT")
     * @Template("BigfootCoreBundle:crud:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {

        return $this->doUpdate($request, $id);
    }
    /**
     * Deletes a BigfootRole entity.
     *
     * @Route("/{id}", name="admin_role_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {

    return $this->doDelete($request, $id);
}
}
