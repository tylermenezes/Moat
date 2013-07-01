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
class EnrollmentCode extends \TinyDb\Orm {
    public static $table_name = 'enrollment_codes';

    public $codeID;
    public $code;
    public $one_time_use;
    public $used_at;

    /**
     * @foreign \Moat\Models\Company company
     */
    public $companyID;

    public function get_has_company()
    {
        return $this->companyID !== null;
    }

    public function get_is_valid()
    {
        return $this->one_time_use || $this->used_at === null;
    }

    public function mark_used()
    {
        if ($this->one_time_use) {
            $this->used_at = time();
            $this->update();
        }
    }
}
