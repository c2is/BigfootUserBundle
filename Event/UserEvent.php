<?php

namespace Bigfoot\Bundle\UserBundle\Event;

/**
 * User Event
 */
final class UserEvent
{
    const RESET_PASSWORD  = 'bigfoot_user.event.user.reset_password';
    const UPDATE_PROFILE  = 'bigfoot_user.event.user.update_profile';
    const UPDATE_PASSWORD = 'bigfoot_user.event.user.update_password';
    const REFRESH_USER    = 'bigfoot_user.event.user.refresh_user';
    const CREATE_PASSWORD = 'bigfoot_user.event.user.create_password';
    const CREATE_FORM     = 'bigfoot_user.event.user.create_form';
}
