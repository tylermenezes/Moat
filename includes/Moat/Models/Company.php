<?php

namespace Moat\Models;


/**
 * Represents a company
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 * @package Moat\Models
 */
class Company extends \TinyDb\Orm {

    public static $table_name = 'companies';

    public $companyID;
    public $name;
    public $description;
    public $is_admin;
    public $is_adviser;

    public $created_at;
    public $modified_at;

    /**
     * @foreign \Moat\Models\Cohort cohort
     */
    public $cohortID;

    public function get_members()
    {
        return User::find()->join('users_companies ON (users_companies.userID = users.userID)')
                           ->where('users_companies.companyID = ?', $this->id)->all();
    }
}
