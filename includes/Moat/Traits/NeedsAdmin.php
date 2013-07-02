<?php

namespace Moat\Traits;

use \Moat\Models;

/**
 * 
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 * @package Moat\Traits
 */
trait NeedsAdmin {
    public function before_check_admin()
    {
        if (!Models\User::me()->is_admin) {
            throw new \CuteControllers\HttpError(403);
        }
    }
}
