<?php

namespace Bigfoot\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Bigfoot\Bundle\CoreBundle\Crud\CrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * BigfootUser controller.
 *
 * @Route("/admin/user")
 */
class BigfootUserController extends CrudController
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'admin_user';
    }

    /**
     * @return string
     */
    protected function getEntity()
    {
        return 'BigfootUserBundle:BigfootUser';
    }

    protected function getFields()
    {
        return array('id' => 'ID', 'username' => 'Username');
    }

    protected function getFormType()
    {
        return 'bigfoot_user';
    }

    protected function getEntityLabel()
    {
        return 'User';
    }

    /**
     * Lists all BigfootUser entities.
     *
     * @Route("/", name="admin_user")
     * @Method("GET")
     * @Template("BigfootCoreBundle:crud:index.html.twig")
     */
    public function indexAction()
    {
        return $this->doIndex();
    }
    /**
     * Creates a new BigfootUser entity.
     *
     * @Route("/", name="admin_user_create")
     * @Method("POST")
     * @Template("BigfootCoreBundle:crud:new.html.twig")
     */
    public function createAction(Request $request)
    {

        return $this->doCreate($request);
    }

    /**
     * Displays a form to create a new BigfootUser entity.
     *
     * @Route("/new", name="admin_user_new")
     * @Method("GET")
     * @Template("BigfootCoreBundle:crud:new.html.twig")
     */
    public function newAction()
    {
        return $this->doNew();
    }

    /**
     * Displays a form to edit an existing BigfootUser entity.
     *
     * @Route("/{id}/edit", name="admin_user_edit")
     * @Method("GET")
     * @Template("BigfootCoreBundle:crud:edit.html.twig")
     */
    public function editAction($id)
    {
        return $this->doEdit($id);
    }

    /**
     * Edits an existing BigfootUser entity.
     *
     * @Route("/{id}", name="admin_user_update")
     * @Method("PUT")
     * @Template("BigfootCoreBundle:crud:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return $this->doUpdate($request, $id);
    }

    /**
     * Deletes a BigfootUser entity.
     *
     * @Route("/{id}", name="admin_user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return $this->doDelete($request, $id);
    }
}
