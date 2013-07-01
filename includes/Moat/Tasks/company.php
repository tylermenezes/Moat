<?php

use \Moat\Models;

/**
 * Manages companies
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 */
class company {
    use \ThinTasks\Task;

    public function main()
    {
        if ($this->kw('cohort')) {
            $cohort = Models\Cohort::find()->where('slug = ?', $this->kw('cohort'))->one();
            $companies = $cohort->companies;
        } else {
            $companies = Models\Company::find()->all();
        }

        echo "Available companies:\n";
        foreach ($companies as $company) {
            echo "  " . $company->id . ": " . $company->name . "\n";
        }
    }

    public function create()
    {
        $this->needs('name', 'description', 'cohort');

        try {
            $cohort = Models\Cohort::find()->where('slug = ?', $this->kw('cohort'))->one();
        } catch (\TinyDb\NoRecordException $ex) {
            echo 'No cohort with that slug found!';
            exit;
        }

        $created_company = new Models\Company([
            'name' => $this->kw('name'),
            'description' => $this->kw('description'),
            'cohortID' => $cohort->id,
            'is_admin' => $this->kw('admin'),
            'is_adviser' => $this->kw('adviser')
        ]);

        echo "Created company with ID " . $created_company->id;
        exit;
    }
}
