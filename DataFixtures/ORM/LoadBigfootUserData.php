<?php

namespace Bigfoot\Bundle\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Bigfoot\Bundle\UserBundle\Entity\BigfootRole;
use Bigfoot\Bundle\UserBundle\Entity\BigfootUser;

class LoadBigfootUserData implements FixtureInterface
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
            $manager->persist($roleAdmin);
        }

        $userAdmin = new BigfootUser();
        $userAdmin
            ->setUsername('admin')
            ->setPassword('c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec')
            ->setSalt('')
            ->setFullname('Administrator')
            ->setEmail('admin@c2is.fr')
            ->setLocale('en')
            ->addUserRole($roleAdmin);

        $manager->persist($userAdmin);
        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }
}