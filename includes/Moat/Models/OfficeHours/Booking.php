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
class Booking extends \TinyDb\Orm {
    public static $table_name = 'officehours_bookings';

    public $bookingID;

    /**
     * @foreign \Moat\Models\User user
     */
    public $userID;

    /**
     * @foreign \Moat\Models\OfficeHours\Slot slot
     */
    public $slotID;
}
