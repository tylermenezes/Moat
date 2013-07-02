<?php

namespace Moat\Models;

use \Moat\Models\Mappings;

/**
 * Class User
 *
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 * @package Moat\Models
 */
class User extends \TinyDb\Orm
{
    use \Jetpack\Traits\User;

    public static $table_name = 'users';

    public $username;
    public $password;
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    protected $photo;

    public $created_at;
    public $modified_at;

    public function get_companies()
    {
        return Company::find()->join('users_companies ON (users_companies.companyID = companies.companyID)')
            ->where('users_companies.userID = ?', $this->id)->all();
    }

    public function get_oauth($service = null)
    {
        if ($service === null) {
            return OAuth::find()->where('userID = ?', $this->id)->all();
        } else {
            return OAuth::find()->where('userID = ?', $this->id)->where('service = ?', $service)->one();
        }
    }

    public function get_formatted_phone()
    {
        return '+1 ('.substr($this->phone, 0, 3).') '.substr($this->phone, 3, 3).'-'.substr($this->phone, 6,4);
    }

    public function get_photo()
    {
        if ($this->photo === null) {
            return 'https://my.studentrnd.org/assets/img/cubes.gif';
        } else {
            return $this->photo;
        }
    }

    public function set_photo($photo)
    {
        $this->photo = $photo;
    }

    public function get_has_photo()
    {
        return $this->photo !== null;
    }

    public function get_name()
    {
        return $this->first_name." ".$this->last_name;
    }

    public function get_facebook()
    {
        return $this->get_oauth('facebook');
    }

    public function get_linkedin()
    {
        return $this->get_oauth('linkedin');
    }

    public function get_twitter()
    {
        return $this->get_oauth('twitter');
    }

    public function get_is_admin()
    {
        return Company::find()->join('users_companies ON (users_companies.companyID = companies.companyID)')
                              ->where('users_companies.userID = ?', $this->id)->where('companies.is_admin')->limit(1)->exists();
    }

    public function get_is_adviser()
    {
        return Company::find()->join('users_companies ON (users_companies.companyID = companies.companyID)')
                              ->where('users_companies.userID = ?', $this->id)->where('companies.is_adviser')->limit(1)->exists();
    }

    public function join(Company $company)
    {
        new Mappings\UserCompany([
            'userID' => $this->id,
            'companyID' => $company->id
        ]);
    }
}
