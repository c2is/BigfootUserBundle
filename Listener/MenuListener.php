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
            $menu->addItem(new Item('toolbar_user_settings', 'Settings', 'user_account', array(), null, 'settings'));
            $menu->addItem(new Item('toolbar_user_logout', 'Logout', 'admin_logout', array(), null, 'off'));
        }
        if ('sidebar_menu' == $menu->getName()) {
            $menu->addItem(new Item('sidebar_user', 'User management', 'admin_user', array(), null, 'user'));
            $userMenu = new Item('sidebar_settings_user', 'User management');
            $userMenu->addChild(new Item('sidebar_settings_role', 'Role management', 'admin_role'));
            $userMenu->addChild(new Item('sidebar_settings_role_menu', 'Menu role management', 'admin_role_menu'));
            $menu->addOnItem('sidebar_settings', $userMenu);
        }
    }
}