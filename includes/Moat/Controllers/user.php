<?php

use \Moat\Models;
use \Moat\Traits;

/**
 * Manages user things on the web.
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 */
class user {
    use \CuteControllers\Controller;
    use Traits\NeedsLogin;

    public function get_index($username)
    {
        try {
            $user = Models\User::find()->where('username = ?', $username)->one();
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(404);
        }

        if (Models\User::me()->username === $username || Models\User::me()->is_admin) {
            echo \Moat::$twig->render('user/edit.html.twig', ['user' => $user]);
        } else {
            echo \Moat::$twig->render('user/view.html.twig', ['user' => $user]);
        }
    }

    public function get_link()
    {
        echo \Moat::$twig->render('user/link.html.twig');
    }

    public function post_index($username)
    {
        try {
            $user = Models\User::find()->where('username = ?', $username)->one();
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(404);
        }

        if (Models\User::me()->username !== $username && !Models\User::me()->is_admin) {
            throw new \CuteControllers\HttpError(403);
        }

        $this->require_post('first_name', 'last_name', 'email', 'phone');
        $phone = preg_replace('/[^0-9]*/', '', $this->request->post('phone'));
        if (strlen($phone) !== 10) {
            echo \Moat::$twig->render('user/edit.html.twig', ['user' => $user, 'invalid_phone' => true]);
            exit;
        }
        if (!filter_var($this->request->post('email'), FILTER_VALIDATE_EMAIL)) {
            echo \Moat::$twig->render('user/edit.html.twig', ['user' => $user, 'invalid_email' => true]);
            exit;
        }

        $user->first_name = $this->request->post('first_name');
        $user->last_name = $this->request->post('last_name');
        $user->phone = $phone;
        $user->email = $this->request->post('email');

        if (Models\User::me()->is_admin) {
            $this->require_post('username');
            $user->username = $this->request->post('username');
        }

        $user->update();
        $this->redirect('/user/'.$username);
    }
}
