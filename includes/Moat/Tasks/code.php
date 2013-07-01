<?php

use \Moat\Models;

/**
 * Deals with EnrollmentCodes.
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 */
class code {
    use \ThinTasks\Task;

    public function mint()
    {
        $code = $this->kw('code') ? $this->kw('code') : self::make_code();

        new Models\EnrollmentCode([
            'code' => $code,
            'one_time_use' => !$this->kw('group'),
            'companyID' => $this->kw('company') ? $this->kw('company') : null
        ]);

        echo "Code created: " . $code;
        exit;
    }

    public function expire($code)
    {
        Models\EnrollmentCode::find()->where('code = ?', $code)->one()->delete();
    }

    private static function make_code()
    {
        $hash = hash('md5', time() . mt_rand(0, mt_getrandmax()));
        return substr(strtoupper($hash), 0, 4) . '-' . substr(strtoupper($hash), 4, 5);
    }
}
