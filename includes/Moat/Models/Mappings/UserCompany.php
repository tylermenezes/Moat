<?php

namespace Moat\Models\Mappings;

/**
 * Maps a user to a company
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 * @package Moat\Models\Mappings
 */
class UserCompany extends \TinyDb\Orm{
    public static $table_name = 'users_companies';

    public $userID;
    public $companyID;
}
