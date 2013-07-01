<?php

use \Moat\Models;

/**
 * Functions related to user management
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 */
class user {
    use \ThinTasks\Task;

    public function main()
    {
        if ($this->kw('cohort')) {
            $cohort = Models\Cohort::find()->where('slug = ?', $this->kw('cohort'))->one();
            $users = $cohort->members;
            echo "Users in the ".$cohort->name." cohort:\n";
        } else if ($this->kw('company')) {
            $company = Models\Company::one($this->kw('company'));
            $users = $company->members;
            echo "Users in ".$company->name.":\n";
        } else {
            $users = Models\User::find()->all();
        }

        foreach ($users as $user) {
            echo "  ".$user->id.": ".$user->username." (".$user->name.")\n";
        }
    }

    public function join($username, $companyID)
    {
        $user = Models\User::find()->where('username = ?', $username)->one();
        $company = Models\Company::one($companyID);

        $user->join($company);
    }
}
