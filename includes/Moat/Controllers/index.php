<?php

use \Moat\Models;
use \Moat\Traits;

/**
 * Class index
 */
class index
{
    use \CuteControllers\Controller;

    public function action_index()
    {
        if (Models\User::is_logged_in()) {
            $this->redirect('/directory');
        } else {
            $this->redirect('/login');
        }
    }
}
