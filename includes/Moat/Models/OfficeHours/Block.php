<?php

namespace Moat\Models;


/**
 * 
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 * @package Moat\Models\OfficeHours
 */
class Block extends \TinyDb\Orm {
    public static $table_name = 'officehours_blocks';

    public $blockID;

    /**
     * @foreign \Moat\Models\User user
     */
    public $userID;
    public $description;
}
