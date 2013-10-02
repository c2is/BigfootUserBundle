<?php

namespace Bigfoot\Bundle\UserBundle\Listener;

use Bigfoot\Bundle\CoreBundle\Event\MenuEvent;
use Bigfoot\Bundle\CoreBundle\Theme\Menu\Item;

class MenuListener
{
    public function onMenuGenerate(MenuEvent $event)
    {
        $menu = $event->getMenu();
        if ('toolbar_menu' == $menu->getName()) {
            $menu->addItem(new Item('toolbar_user_settings', 'Settings', 'user_account', array(), array(), 'settings'));
            $menu->addItem(new Item('toolbar_user_logout', 'Logout', 'admin_logout', array(), array(), 'off'));
        }
        if ('sidebar_menu' == $menu->getName()) {
            $menu->addItem(new Item('sidebar_user', 'User', 'admin_user', array(), null, 'group'));
            $userMenu = new Item('sidebar_settings_user', 'User', null, array(), array(), 'group');
            $userMenu->addChild(new Item('sidebar_settings_role', 'Role', 'admin_role', array(), array(), 'legal'));
            $userMenu->addChild(new Item('sidebar_settings_role_menu', 'Menu access', 'admin_role_menu', array(), array(), null));
            $menu->addOnItem('sidebar_settings', $userMenu);
        }
    }
}
