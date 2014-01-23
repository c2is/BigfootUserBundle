<?php

namespace Bigfoot\Bundle\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Bigfoot\Bundle\UserBundle\Entity\BigfootRole;

class LoadBigfootRoleData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $roleAdmin = new BigfootRole();
        $roleAdmin
            ->setName('ROLE_ADMIN')
            ->setLabel('Admin');

        $this->setReference('ROLE_ADMIN', $roleAdmin);

        $roleUser = new BigfootRole();
        $roleUser
            ->setName('ROLE_USER')
            ->setLabel('User');

        $this->setReference('ROLE_USER', $roleUser);

        $repository = $manager->getRepository('Gedmo\Translatable\Entity\Translation');
        $repository->translate($roleAdmin, 'label', 'fr', 'Administrateur');
        $repository->translate($roleUser, 'label', 'fr', 'Utilisateur');

        $manager->persist($roleAdmin);
        $manager->persist($roleUser);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}