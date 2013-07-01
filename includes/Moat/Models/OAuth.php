<?php

namespace Moat\Models;


/**
 * Holds information about OAuth connections for users
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 * @package Moat\Models
 */
class OAuth extends \TinyDb\Orm {

    public static $table_name = 'users_oauth';

    public $oauthID;
    public $service;
    public $service_user_id;
    public $access_token;
    public $expires_at;

    /**
     * @foreign \Moat\Models\User user
     */
    public $userID;
}
