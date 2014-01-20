<?php

namespace Bigfoot\Bundle\UserBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Bigfoot\Bundle\UserBundle\Entity\BigfootRole;
use Bigfoot\Bundle\UserBundle\Entity\BigfootUser;

class LoadBigfootUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    public $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('bigfoot_user.manager.user');
        $roleAdmin   = $this->getReference('ROLE_ADMIN');

        $admin = $userManager
            ->createUser()
            ->setUsername('admin')
            ->setPlainPassword('admin')
            ->setFullname('Administrator')
            ->setEmail('admin@c2is.fr')
            ->setLocale('en')
            ->addRole($roleAdmin);
        $userManager->updatePassword($admin);

        $manager->persist($admin);
        $manager->flush();
    }

    public function getOrder()
    {
        return 6;
    }
}