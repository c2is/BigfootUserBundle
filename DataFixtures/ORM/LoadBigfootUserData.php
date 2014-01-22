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

        $admin2 = $userManager
            ->createUser()
            ->setUsername('admin2')
            ->setPlainPassword('admin2')
            ->setFullname('Administrator2')
            ->setEmail('admin2@c2is.fr')
            ->setLocale('en');
        $userManager->updatePassword($admin2);

        $admin3 = $userManager
            ->createUser()
            ->setUsername('admin3')
            ->setPlainPassword('admin3')
            ->setFullname('Administrator3')
            ->setEmail('admin3@c2is.fr')
            ->setLocale('en');
        $userManager->updatePassword($admin3);

        $admin4 = $userManager
            ->createUser()
            ->setUsername('admin4')
            ->setPlainPassword('admin4')
            ->setFullname('Administrator4')
            ->setEmail('admin4@c2is.fr')
            ->setLocale('en');
        $userManager->updatePassword($admin4);

        $admin5 = $userManager
            ->createUser()
            ->setUsername('admin5')
            ->setPlainPassword('admin5')
            ->setFullname('Administrator5')
            ->setEmail('admin5@c2is.fr')
            ->setLocale('en');
        $userManager->updatePassword($admin5);

        $admin6 = $userManager
            ->createUser()
            ->setUsername('admin6')
            ->setPlainPassword('admin6')
            ->setFullname('Administrator6')
            ->setEmail('admin6@c2is.fr')
            ->setLocale('en');
        $userManager->updatePassword($admin6);

        $admin7 = $userManager
            ->createUser()
            ->setUsername('admin7')
            ->setPlainPassword('admin7')
            ->setFullname('Administrator7')
            ->setEmail('admin7@c2is.fr')
            ->setLocale('en');
        $userManager->updatePassword($admin7);

        $admin8 = $userManager
            ->createUser()
            ->setUsername('admin8')
            ->setPlainPassword('admin8')
            ->setFullname('Administrator8')
            ->setEmail('admin8@c2is.fr')
            ->setLocale('en');
        $userManager->updatePassword($admin8);

        $admin9 = $userManager
            ->createUser()
            ->setUsername('admin9')
            ->setPlainPassword('admin9')
            ->setFullname('Administrator9')
            ->setEmail('admin9@c2is.fr')
            ->setLocale('en');
        $userManager->updatePassword($admin9);

        $admin10 = $userManager
            ->createUser()
            ->setUsername('admin10')
            ->setPlainPassword('admin10')
            ->setFullname('Administrator10')
            ->setEmail('admin10@c2is.fr')
            ->setLocale('en');
        $userManager->updatePassword($admin10);

        $admin11 = $userManager
            ->createUser()
            ->setUsername('admin11')
            ->setPlainPassword('admin11')
            ->setFullname('Administrator11')
            ->setEmail('admin11@c2is.fr')
            ->setLocale('en');
        $userManager->updatePassword($admin11);

        $admin12 = $userManager
            ->createUser()
            ->setUsername('admin12')
            ->setPlainPassword('admin12')
            ->setFullname('Administrator12')
            ->setEmail('admin12@c2is.fr')
            ->setLocale('en');
        $userManager->updatePassword($admin12);

        $admin13 = $userManager
            ->createUser()
            ->setUsername('admin13')
            ->setPlainPassword('admin13')
            ->setFullname('Administrator13')
            ->setEmail('admin13@c2is.fr')
            ->setLocale('en');
        $userManager->updatePassword($admin13);

        $admin14 = $userManager
            ->createUser()
            ->setUsername('admin14')
            ->setPlainPassword('admin14')
            ->setFullname('Administrator')
            ->setEmail('admin14@c2is.fr')
            ->setLocale('en');
        $userManager->updatePassword($admin14);

        $manager->persist($admin);
        $manager->persist($admin2);
        $manager->persist($admin3);
        $manager->persist($admin4);
        $manager->persist($admin5);
        $manager->persist($admin6);
        $manager->persist($admin7);
        $manager->persist($admin8);
        $manager->persist($admin9);
        $manager->persist($admin10);
        $manager->persist($admin11);
        $manager->persist($admin12);
        $manager->persist($admin13);
        $manager->persist($admin14);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}