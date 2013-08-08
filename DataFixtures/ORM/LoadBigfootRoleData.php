<?php

namespace Bigfoot\Bundle\UserBundle\DataFixtures\ORM;

use Bigfoot\Bundle\UserBundle\Entity\BigfootRole;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadBigfootRoleData implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $roleRespository = $manager->getRepository('BigfootUserBundle:BigfootRole');
        $roleAdmin = $roleRespository->findOneBy(array('name' => 'ROLE_ADMIN'));

        if (!$roleAdmin) {
            $roleAdmin = new BigfootRole();
            $roleAdmin->setName('ROLE_ADMIN');
            $roleAdmin->setLabel('Administrator');
        }

        $roleUser = new BigfootRole();
        $roleUser->setName('ROLE_USER');
        $roleUser->setLabel('User');

        $repository = $manager->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        $repository->translate($roleAdmin, 'label', 'fr', 'Administrateur');
        $repository->translate($roleUser, 'label', 'fr', 'Utilisateur');

        $manager->persist($roleAdmin);
        $manager->persist($roleUser);
        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }
}