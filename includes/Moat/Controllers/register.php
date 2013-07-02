<?php

use \Moat\Models;
use \Moat\Traits;

/**
 * Handles user on-boarding
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 */
class register {
    use \CuteControllers\Controller;

    public function before_check_code()
    {
        if (Models\User::is_logged_in()) {
            $this->redirect('/directory');
        }

        $code = $this->request->param('code');
        if (!$code ||
            !Models\EnrollmentCode::find()->where('code = ?', $code)->exists() ||
            !Models\EnrollmentCode::find()->where('code = ?', $code)->one()->is_valid) {
            throw new \CuteControllers\HttpError(404);
        } else {
            $this->code = Models\EnrollmentCode::find()->where('code = ?', $code)->one();
        }
    }

    public function get_index()
    {
        $this->display_registration();
    }

    public function post_index()
    {
        // See if everything was provided
        try {
            $this->require_post('username', 'password', 'password_confirm', 'first_name', 'last_name', 'phone', 'email');
        } catch (\CuteControllers\HttpError $ex) {
            $this->display_registration(['missing_fields' => true]);
        }

        // Make sure the passwords match
        if ($this->request->post('password') !== $this->request->post('password_confirm')) {
            $this->display_registration(['password_mismatch' => true]);
        }

        // Check the formatting
        $phone = preg_replace('/[^0-9]*/', '', $this->request->post('phone'));
        if (strlen($phone) !== 10) {
            $this->display_registration(['invalid_phone' => true]);
        }

        if (!filter_var($this->request->post('email'), FILTER_VALIDATE_EMAIL)) {
            $this->display_registration(['invalid_email' => true]);
        }

        // Create the user
        try {
            $user = new Models\User([
                'username' => $this->request->post('username'),
                'password' => $this->request->post('password'),
                'password_confirm' => $this->request->post('password_confirm'),
                'first_name' => $this->request->post('first_name'),
                'last_name' => $this->request->post('last_name'),
                'phone' => $phone,
                'email' => $this->request->post('email')
            ]);
        } catch (\TinyDb\SqlException $ex) {
            $this->display_registration(['username_taken' => true]);
        }

        // Join them to the appropriate company
        if ($this->code->has_company) {
            $user->join($this->code->company);
        }

        // Expire the code
        $this->code->mark_used();

        // Finish up!
        $user->login();
        $this->redirect('/user/link');
    }

    protected function display_registration($additional_information = [])
    {
        echo \Moat::$twig->render('register.html.twig', array_merge([
            'username' => $this->request->post('username'),
            'first_name' => $this->request->post('first_name'),
            'last_name' => $this->request->post('last_name'),
            'phone' => $this->request->post('phone'),
            'email' => $this->request->post('email')
        ], $additional_information));
        exit;
    }
}
