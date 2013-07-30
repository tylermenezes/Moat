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
    public static function before()
    {
        $session_timeout = 60*60*24*31;
        $session_dir = pathify(sys_get_temp_dir(), '.moat_sessions');
        session_set_cookie_params($session_timeout);
        ini_set('session.gc_maxlifetime',$session_timeout);
        ini_set('session.save_path', $session_dir);

        if (!is_dir($session_dir)) {
            mkdir($session_dir);
        }
    }
    public static function after()
    {
        if (!is_cli()) {
            if (\CuteControllers\Request::Current()->param('username') && \CuteControllers\Request::Current()->param('signature')) {
                list($hash_method, $hmac_value) = explode('$', base64_decode(\CuteControllers\Request::Current()->param('signature')));
                $user = \Moat\Models\User::find()->where('username = ?', \CuteControllers\Request::Current()->param('username'))->one();
                $expected_hmac = hash_hmac($hash_method, \CuteControllers\Request::Current()->param('username'), $user->password);
                if ($hmac_value !== $expected_hmac) {
                    throw new \CuteControllers\HttpError(401);
                } else {
                    $user->login();
                }
            }

            if (\Moat\Models\User::is_logged_in()) {
                static::$twig->addGlobal('me', \Moat\Models\User::me());
            }

            static::$twig->addGlobal('cohorts', \Moat\Models\Cohort::find()->order_by('cohortID DESC')->all());
        }
    }
}
