<?php

use \Moat\Models;
use \Moat\Traits;

/**
 * Allows quickly friending everyone in the cohort.
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 */
class friends {
    use \CuteControllers\Controller;
    use Traits\NeedsCohort;
    use Traits\NeedsLogin;

    public function action_index()
    {
        // TODO
    }

}
