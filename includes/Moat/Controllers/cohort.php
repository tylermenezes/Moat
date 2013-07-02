<?php

use \Moat\Models;
use \Moat\Traits;

/**
 * 
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 */
class cohort {
    use \CuteControllers\Controller;
    use Traits\NeedsLogin;

    public function action_index($new)
    {
        Models\Cohort::find()->where('slug = ?', $new)->one()->set_current();
        $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
