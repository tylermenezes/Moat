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
class invite {
    use \CuteControllers\Controller;
    use Moat\Traits\NeedsCohort;
    use Moat\Traits\NeedsAdmin;

    public function get_index()
    {
        echo \Moat::$twig->render('user/invite.html.twig');
    }

    public function post_index()
    {
        $code = $this->request->post('code') ? $this->request->post('code') : self::make_code();

        new Models\EnrollmentCode([
            'code' => $code,
            'one_time_use' => !$this->request->post('group'),
            'companyID' => $this->request->post('company') ? $this->request->post('company') : null
        ]);

        if ($this->request->post('email')) {
            mail($this->request->post('email'), 'Invite to Moat', \Moat::$twig->render('emails/invite.txt.twig', [
                    'link' => \CuteControllers\Router::link('/register?code='.$code)
                ]),
                'From: "Moat" <moat@studentrnd.org>');
            $this->redirect('/user/invite');
        } else {
            echo \Moat::$twig->render('user/invite.html.twig', ['code' => $code]);
        }
    }

    private static function make_code()
    {
        $hash = hash('md5', time() . mt_rand(0, mt_getrandmax()));
        return substr(strtoupper($hash), 0, 4) . '-' . substr(strtoupper($hash), 4, 5);
    }
}
