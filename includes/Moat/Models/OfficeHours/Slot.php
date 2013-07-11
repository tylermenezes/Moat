<?php

namespace Moat\Models\OfficeHours;


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
    public $ends_at;

    /**
     * @foreign \Moat\Models\User user
     */
    public $userID;

    public $created_at;
    public $updated_at;
    public $noshow;

    public function get_has_started()
    {
        return time() >= $this->starts_at;
    }
}
