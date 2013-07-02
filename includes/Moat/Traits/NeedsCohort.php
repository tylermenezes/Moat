<?php

namespace Moat\Traits;

use \Moat\Models;

/**
 * Sets the cohort for the current page.
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 */
trait NeedsCohort {
    public function before_set_cohort()
    {
        // See if the user doesn't have a cohort selected
        if (!Models\Cohort::has_current()) {
            // Are they in a company?
            if (count(Models\User::me()->companies) > 0) {
                // Pick their most recent company
                $companies = Models\User::me()->companies;
                $companies[count($companies) - 1]->cohort->set_current();
            } else {
                // Pick the most recent cohort
                Models\Cohort::find()->order_by('cohortID DESC')->one()->set_current();
            }
        }

        // Set the cohort in Twig
        \Moat::$twig->addGlobal('current_cohort', Models\Cohort::current());
    }
}
