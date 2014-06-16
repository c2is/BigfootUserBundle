<?php

namespace Bigfoot\Bundle\UserBundle\Manager;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

use Bigfoot\Bundle\CoreBundle\Menu\Builder;
use Bigfoot\Bundle\UserBundle\Entity\RoleMenu;

class RoleMenuManager
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Builder
     */
    private $builder;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * Constructor
     *
     * @param Request       $request
     * @param Builder       $builder
     * @param EntityManager $entityManager
     */
    public function __construct(Request $request, Builder $builder, EntityManager $entityManager)
    {
        $this->request       = $request;
        $this->builder       = $builder;
        $this->entityManager = $entityManager;
        $this->repository    = $this->entityManager->getRepository('BigfootUserBundle:RoleMenu');
    }

    /**
     * Get items
     *
     * @return array
     */
    public function getItems()
    {
        $menu  = $this->builder->createMainMenu($this->request);
        $items = $this->getChilds($menu->getChildren());

        return $items;
    }

    /**
     * Get childs
     *
     * @param  array   $items
     * @param  integer $level
     *
     * @return array
     */
    public function getChilds($items, $level = 0)
    {
        $childrens = array();

        foreach ($items as $item) {
            $roleMenu = $this->repository->findOneBySlug($item->getName());
            $child    = $roleMenu instanceof RoleMenu ? $roleMenu : new RoleMenu();
            $child
                ->setSlug($item->getName())
                ->setLabel($item->getLabel())
                ->setLevel($level);

            $childrens[]  = $child;

            if (count($item->getChildren())) {
                $elements  = $this->getChilds($item->getChildren(), $level + 1);
                $childrens = array_merge($childrens, $elements);
            }
        }

        return $childrens;
    }
}
