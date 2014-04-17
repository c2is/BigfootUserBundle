<?php

namespace Bigfoot\Bundle\UserBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    public $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
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

        $user = $userManager
            ->createUser()
            ->setUsername('yehya')
            ->setPlainPassword('aaaaaa')
            ->setFullname('Yehya LATTI')
            ->setEmail('y.latti@c2is.fr')
            ->setLocale('en');
        $userManager->updatePassword($user);

        $user2 = $userManager
            ->createUser()
            ->setUsername('jean')
            ->setPlainPassword('aaaaaa')
            ->setFullname('Jean Jack')
            ->setEmail('j.jack@c2is.fr')
            ->setLocale('en');
        $userManager->updatePassword($user2);

        $manager->persist($admin);
        $manager->persist($user);
        $manager->persist($user2);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}