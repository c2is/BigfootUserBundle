<?php

namespace Bigfoot\Bundle\UserBundle\Listener;

use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Bigfoot\Bundle\CoreBundle\Event\MenuEvent;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Menu Listener
 */
class MenuListener implements EventSubscriberInterface
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
            MenuEvent::GENERATE_MAIN => 'onGenerateMain',
        );
    }

    /**
     * @param GenericEvent $event
     */
    public function onGenerateMain(GenericEvent $event)
    {
        $menu     = $event->getSubject();
        $userMenu = $menu->getChild('user');

        if ($this->security->isGranted('ROLE_ADMIN')) {
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
