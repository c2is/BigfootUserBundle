<?php

namespace Bigfoot\Bundle\UserBundle\Subscriber;

use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

use Bigfoot\Bundle\CoreBundle\Event\MenuEvent;

/**
 * Menu Subscriber
 */
class MenuSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     */
    private $security;

    /**
     * @param SecurityContextInterface $security
     */
    public function __construct(SecurityContextInterface $security)
    {
        $this->security = $security;
    }

    /**
     * Get subscribed events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            MenuEvent::GENERATE_MAIN => array('onGenerateMain', 1)
        );
    }

    /**
     * @param GenericEvent $event
     */
    public function onGenerateMain(GenericEvent $event)
    {
        $menu = $event->getSubject();
        $root = $menu->getRoot();

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $userMenu = $root->addChild(
                'user',
                array(
                    'label'          => 'Users',
                    'url'            => '#',
                    'linkAttributes' => array(
                        'class' => 'dropdown-toggle',
                        'icon'  => 'group',
                    )
                )
            );

            $userMenu->setChildrenAttributes(
                array(
                    'class' => 'submenu',
                )
            );

            $userMenu->addChild(
                'user',
                array(
                    'label'  => 'User',
                    'route'  => 'admin_user',
                    'extras' => array(
                        'routes' => array(
                            'admin_user_new',
                            'admin_user_edit'
                        )
                    ),
                    'linkAttributes' => array(
                        'icon' => 'user',
                    )
                )
            );

            $userMenu->addChild(
                'role',
                array(
                    'label'  => 'Role',
                    'route'  => 'admin_role',
                    'extras' => array(
                        'routes' => array(
                            'admin_role_new',
                            'admin_role_edit'
                        )
                    ),
                    'linkAttributes' => array(
                        'icon' => 'legal',
                    )
                )
            );
        }
    }
}