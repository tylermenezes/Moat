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
class Deal extends \TinyDb\Orm {
    public static $table_name = 'deals';

    public $dealID;
    public $company;
    public $url;
    public $details;
    public $approved;
    public $redemption;

    /**
     * @foreign \Moat\Models\Cohort cohort
     */
    public $cohortID;
}
