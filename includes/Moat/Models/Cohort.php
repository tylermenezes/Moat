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
class Cohort extends \TinyDb\Orm {

    use \Jetpack\Traits\SessionModel;

    public static $table_name = 'cohorts';

    public $cohortID;
    public $name;
    public $slug;

    public function get_companies()
    {
        return Company::find()->where('cohortID = ?', $this->id)->order_by('is_admin ASC, is_adviser ASC, name')->all();
    }

    public function get_members()
    {
        return User::find()->join('users_companies ON (users_companies.userID = users.userID)')
                           ->join('companies ON (users_companies.companyID = companies.companyID)')
                           ->group_by('userID')
                           ->where('companies.cohortID = ?', $this->cohortID)->all();
    }

    public function get_deals($only_approved = true)
    {
        $query = Deal::find()->where('cohortID = ? OR cohortID IS NULL', $this->id);
        if ($only_approved) {
            $query->where('approved = 1');
        }

        return $query->all();
    }
}
