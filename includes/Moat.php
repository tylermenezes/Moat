<?php

require_once('submodules/Jetpack/Jetpack/App.php');

/**
 * The app
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 */
class Moat extends \Jetpack\App
{
    public static function after()
    {
        if (\Moat\Models\User::is_logged_in()) {
            static::$twig->addGlobal('me', \Moat\Models\User::me());
        }

        static::$twig->addGlobal('cohorts', \Moat\Models\Cohort::find()->order_by('cohortID DESC')->all());
    }
}
