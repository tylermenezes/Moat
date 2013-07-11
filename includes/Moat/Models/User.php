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
    public $bio;
    public $email;
    public $phone;
    public $photo;

    public $created_at;
    public $modified_at;

    public function get_signature_fragment()
    {
        $hmac = hash_hmac('sha512', $this->username, $this->password);
        $enc = base64_encode(implode('$', ['sha512', $hmac]));
        return implode('&', ['username=' . urlencode($this->username), 'signature=' . urlencode($enc)]);
    }

    public function friends_with($network, $user)
    {
        if ($this->get_oauth($network) === null || $user->get_oauth($network) === null) {
            return true;
        }

        $providers = [
            'facebook' => '\\Moat\\FriendshipProviders\\Facebook',
            'twitter' => '\\Moat\\FriendshipProviders\\Twitter',
            'linkedin' => '\\Moat\\FriendshipProviders\\LinkedIn'
        ];
        $provider = $providers[$network];
        return $provider::is_friends($user->get_oauth($network)->service_user_id, $this->get_oauth($network)->access_token);
    }

    public function facebook_friends_with($user)
    {
        return $this->friends_with('facebook', $user);
    }

    public function linkedin_friends_with($user)
    {
        return $this->friends_with('linkedin', $user);
    }

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
            try {
                return OAuth::find()->where('userID = ?', $this->id)->where('service = ?', $service)->one();
            } catch (\TinyDb\NoRecordException $ex) {
                return null;
            }
        }
    }

    public function get_formatted_phone()
    {
        return '+1 ('.substr($this->phone, 0, 3).') '.substr($this->phone, 3, 3).'-'.substr($this->phone, 6,4);
    }

    public function get_photo_websafe()
    {
        if ($this->photo === null) {
            return 'https://my.studentrnd.org/assets/img/cubes.gif';
        } else {
            return $this->photo;
        }
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

    public function quit(Company $company)
    {
        Mappings\UserCompany::find()->where('companyID = ?', $company->id)->where('userID = ?', $this->id)->one()->delete();
    }

    public function in_company(Company $company)
    {
        return Mappings\UserCompany::find()->where('userID = ?', $this->id)->where('companyID = ?', $company->id)->limit(1)->exists();
    }
}
