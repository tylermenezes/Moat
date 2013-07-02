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
class index {
    use \CuteControllers\Controller;
    use Traits\NeedsLogin;
    use Traits\NeedsCohort;

    public function get_index($username)
    {
        try {
            $user = Models\User::find()->where('username = ?', $username)->one();
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(404);
        }

        echo \Moat::$twig->render('user/view.html.twig', ['user' => $user]);
    }

    public function get_link()
    {
        echo \Moat::$twig->render('user/link.html.twig');
    }

    public function get_impersonate($username)
    {
        if (!Models\User::me()->is_admin) {
            throw new \CuteControllers\HttpError(403);
        }

        try {
            $user = Models\User::find()->where('username = ?', $username)->one();
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(404);
        }

        $user->login();
        $this->redirect('/');
    }

    public function get_edit($username)
    {
        try {
            $user = Models\User::find()->where('username = ?', $username)->one();
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(404);
        }

        if (Models\User::me()->username !== $username && !Models\User::me()->is_admin) {
            throw new \CuteControllers\HttpError(403);
        }

        echo \Moat::$twig->render('user/edit.html.twig', ['user' => $user]);
    }

    public function post_edit($username)
    {
        try {
            $user = Models\User::find()->where('username = ?', $username)->one();
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(404);
        }

        if (Models\User::me()->username !== $username && !Models\User::me()->is_admin) {
            throw new \CuteControllers\HttpError(403);
        }

        try {
            $this->require_post('first_name', 'last_name', 'email', 'phone');
        } catch (\CuteControllers\HttpError $er) {
            echo \Moat::$twig->render('user/edit.html.twig', ['user' => $user, 'missing_field' => true]);
            exit;
        }


        $phone = preg_replace('/[^0-9]*/', '', $this->request->post('phone'));
        if (strlen($phone) !== 10) {
            echo \Moat::$twig->render('user/edit.html.twig', ['user' => $user, 'invalid_phone' => true]);
            exit;
        }
        if (!filter_var($this->request->post('email'), FILTER_VALIDATE_EMAIL)) {
            echo \Moat::$twig->render('user/edit.html.twig', ['user' => $user, 'invalid_email' => true]);
            exit;
        }


        if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] === 0) {
            $image = new \Image($_FILES["photo"]["tmp_name"]);
            $image->fill(200, 200);
            $filename = md5(time() . rand(0,50000)) . '.jpg';
            $image->save(pathify(\Moat::$dir->webroot, 'assets', 'uploads', $filename));
            $user->photo = '/assets/uploads/'.$filename;
        }

        $user->first_name = $this->request->post('first_name');
        $user->last_name = $this->request->post('last_name');
        $user->phone = $phone;
        $user->email = $this->request->post('email');
        $user->bio = $this->request->post('bio');

        if (Models\User::me()->is_admin) {
            $this->require_post('username');
            $user->username = $this->request->post('username');
        }

        $user->update();
        $this->redirect('/user/'.$username);
    }
}
