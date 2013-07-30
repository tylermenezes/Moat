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
class reset_password {
    use \CuteControllers\Controller;
    use Traits\NeedsCohort;
    use Traits\NeedsAdmin;
    use Traits\NeedsLogin;

    public function get_index($username)
    {
        try {
            $user = Models\User::find()->where('username = ?', $username)->one();
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(404);
        }

        echo \Moat::$twig->render('user/reset_password.html.twig', ['user' => $user]);
    }

    public function post_index($username)
    {
        try {
            $user = Models\User::find()->where('username = ?', $username)->one();
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(404);
        }

        $newpass = $user->reset_password();
        $template = $this->request->post('template') === 'welcome' ? 'welcome' : 'reset_password';

        mail($user->email, 'Your Moat Login Details', \Moat::$twig->render('emails/'.$template.'.txt.twig', [
                'link' => \CuteControllers\Router::link('/login'),
                'username' => $username,
                'password' => $newpass
            ]),
            'From: "Moat" <moat@studentrnd.org>');
        $this->redirect('/user/'.$username);
    }
}
