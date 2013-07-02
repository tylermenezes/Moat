<?php

namespace Moat\FriendshipProviders;


/**
 * 
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 * @package Moat\FriendshipProviders
 */
class Facebook {

    protected static $data_cache = [];

    public static function is_friends($with, $access_token)
    {
        if ($with === null) {
            return false;
        }

        if (!array_key_exists($access_token, static::$data_cache)) {
            $url = "https://graph.facebook.com/me/friends?access_token=".$access_token;
            $data = json_decode(file_get_contents($url), true);
            static::$data_cache[$access_token] = $data;
        }

        foreach (static::$data_cache[$access_token]['data'] as $person) {
            if ($person['id'] === $with) {
                return true;
            }
        }

        return false;
    }
}
