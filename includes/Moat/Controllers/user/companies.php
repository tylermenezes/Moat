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
class companies {
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

        echo \Moat::$twig->render('user/companies.html.twig', ['user' => $user]);
    }

    public function post_add($username)
    {
        try {
            $user = Models\User::find()->where('username = ?', $username)->one();
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(404);
        }

        $company = Models\Company::one($this->request->post('add'));
        $user->join($company);
        $this->redirect('/user/'.$username);
    }

    public function post_remove($username)
    {
        try {
            $user = Models\User::find()->where('username = ?', $username)->one();
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(404);
        }

        $company = Models\Company::one($this->request->post('remove'));
        $user->quit($company);
        $this->redirect('/user/'.$username);
    }
}
