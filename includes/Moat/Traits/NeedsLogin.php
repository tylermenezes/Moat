<?php

namespace Moat\Traits;

use \Moat\Models;


/**
 * Requires the user to be logged in to view the page.
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 * @package Moat\Traits
 */
trait NeedsLogin {
    public function before_check_login()
    {
        if (!Models\User::is_logged_in()) {
            throw new \CuteControllers\HttpError(401);
        }
    }
}
