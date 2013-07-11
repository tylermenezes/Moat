<?php

namespace Moat\Traits;

use \Moat\Models;


/**
 * Requires the user to be logged in to view the page.
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 * @package Moat\Traits
 */
trait NeedsLogin {
    public function before_check_login()
    {
        if ($this->request->param('username') && $this->request->param('signature')) {
            list($hash_method, $hmac_value) = explode('$', base64_decode($this->request->param('signature')));
            $user = Models\User::find()->where('username = ?', $this->request->param('username'))->one();
            $expected_hmac = hash_hmac($hash_method, $this->request->param('username'), $user->password);
            if ($hmac_value !== $expected_hmac) {
                throw new \CuteControllers\HttpError(401);
            } else {
                $user->login();
            }
        }

        if (!Models\User::is_logged_in()) {
            throw new \CuteControllers\HttpError(401);
        }
    }
}
