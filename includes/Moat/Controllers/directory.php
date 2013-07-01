<?php

use \Moat\Models;
use \Moat\Traits;

/**
 * Shows the directory of people
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 */
class directory_c {
    use \CuteControllers\Controller;
    use Traits\NeedsCohort;
    use Traits\NeedsLogin;

    public function action_index()
    {
        echo \Moat::$twig->render('directory.html.twig');
    }

    public function action_cohort($new)
    {
        Models\Cohort::find()->where('slug = ?', $new)->one()->set_current();
        $this->redirect('/directory');
    }
}
