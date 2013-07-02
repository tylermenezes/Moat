<?php

namespace Moat\Models;


/**
 * 
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 * @package Moat\Models
 */
class Slot extends \TinyDb\Orm {
    public static $table_name = 'officehours_slots';

    public $slotID;

    /**
     * @foreign \Moat\Models\OfficeHours\Block block
     */
    public $blockID;
    public $starts_at;
}
