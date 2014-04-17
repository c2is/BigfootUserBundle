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
                    'label'          => 'bigfoot.sidebar_menu.user.title',
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
                    'label'  => 'bigfoot.sidebar_menu.user.level_1.user',
                    'route'  => 'bigfoot_user',
                    'extras' => array(
                        'routes' => array(
                            'bigfoot_user_new',
                            'bigfoot_user_edit'
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
                    'label'  => 'bigfoot.sidebar_menu.user.level_1.role',
                    'route'  => 'bigfoot_role',
                    'extras' => array(
                        'routes' => array(
                            'bigfoot_role_new',
                            'bigfoot_role_edit'
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