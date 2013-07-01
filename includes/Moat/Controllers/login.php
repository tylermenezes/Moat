<?php

use \Moat\Models;
use \Moat\Traits;

/**
 *
 *
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 */
class Login {
    use \CuteControllers\Controller;

    public function get_index()
    {
        echo \Moat::$twig->render('login.html.twig');
    }

    public function post_index()
    {
        // Check the username
        try {
            $user = Models\User::find()->where('username = ?', $this->request->post('username'))->one();
        } catch (\TinyDb\NoRecordException $ex) {
            echo \Moat::$twig->render('login.html.twig', ['invalid_login' => true]);
            return;
        }

        // Check the password
        if (!$user->check_password($this->request->post('password'))) {
            echo \Moat::$twig->render('login.html.twig', ['invalid_login' => true]);
            return;
        }

        $user->login();
        $this->redirect('/directory');
    }

    public function action_logout()
    {
        Models\User::logout();
        $this->redirect('/');
    }
}
