<?php

use \Moat\Models;

/**
 * Manages cohorts
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 */
class cohort {
    use \ThinTasks\Task;

    public function main()
    {
        echo "Available cohorts:\n";
        foreach (Models\Cohort::find()->all() as $cohort) {
            echo "  " . $cohort->slug . ": " . $cohort->name . "\n";
        }
    }

    public function create()
    {
        $this->needs('name', 'slug');

        $created_cohort = new Models\Cohort([
            'name' => $this->args->keyword['name'],
            'slug' => $this->args->keyword['slug']
        ]);

        echo "Created cohort with ID " . $created_cohort->id;
    }

    public function delete($slug)
    {
        try {
            $cohort = Models\Cohort::find()->where('slug = ?', $slug)->one();
            $cohort->delete();
            echo "Deleted cohort.";
        } catch (\TinyDb\NoRecordException $ex) {
            echo "Cohort not found!";
        }
    }
}
