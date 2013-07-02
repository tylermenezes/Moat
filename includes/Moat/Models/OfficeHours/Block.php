<?php

namespace Moat\Models\OfficeHours;


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
    public $starts_at;

    public function get_slots()
    {
        return Slot::find()->where('blockID = ?', $this->id)->all();
    }
}
