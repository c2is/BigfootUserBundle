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
            $menu->addItem(new Item('toolbar_user_settings', 'Settings', 'user_account', array(), null, 'cogs'));
            $menu->addItem(new Item('toolbar_user_logout', 'Logout', 'admin_logout', array(), null, 'off'));
        }
        if ('sidebar_menu' == $menu->getName()) {
            $menu->addItem(new Item('sidebar_user', 'Users', 'admin_user', array(), null, 'user'));
            $userMenu = new Item('sidebar_settings_user', 'User Management');
            $userMenu->addChild(new Item('sidebar_settings_role', 'Role Management', 'admin_role'));
            $menu->addOnItem('sidebar_settings', $userMenu);
        }
    }
}