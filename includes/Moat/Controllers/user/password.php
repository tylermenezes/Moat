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
    use Traits\NeedsLogin;

    public function get_index($username)
    {
        try {
            $user = Models\User::find()->where('username = ?', $username)->one();
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(404);
        }

        if ($user !== Models\User::me() && !Models\User::me()->is_admin) {
            throw new \CuteControllers\HttpError(403);
        }

        echo \Moat::$twig->render('user/password.html.twig', ['user' => $user]);
    }

    public function post_index($username)
    {
        try {
            $user = Models\User::find()->where('username = ?', $username)->one();
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(404);
        }

        if ($user !== Models\User::me() && !Models\User::me()->is_admin) {
            throw new \CuteControllers\HttpError(403);
        }

        if (!$this->request->post('password')) {
            echo \Moat::$twig->render('user/password.html.twig', ['user' => $user, 'error' => 'Please enter a password.']);
            exit;
        }

        if ($this->request->post('password') !== $this->request->post('password_confirm')) {
            echo \Moat::$twig->render('user/password.html.twig', ['user' => $user, 'error' => 'Passwords did not match.']);
            exit;
        }

        $was_reset_required = $user->password_reset_required;

        $user->password_reset_required = false;
        $user->set_password($this->request->post('password'));
        $user->update();

        if ($was_reset_required) {
            $this->redirect('/');
        } else {
            $this->redirect('/user/'.$username);
        }
    }
}
