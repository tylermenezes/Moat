<?php

namespace Moat\OAuthProviders;

use \Moat\Models;

/**
 * 
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 */
class LinkedIn implements IOAuthProvider {
    protected $secret;

    public function set_return_uri($new)
    {
        $this->return_uri = $new;
    }

    public function get_redirect_url()
    {
        return "https://www.linkedin.com/uas/oauth2/authorization?response_type=code&client_id=".\Moat::$config->linkedin->public
               ."&scope=r_basicprofile,r_network&state=".Models\User::me()->id."&redirect_uri=".$this->return_uri;
    }

    public function get_info($code)
    {
        if ($code === null) {
            throw new OAuthDeniedException();
        }

        $exchange_uri = "https://www.linkedin.com/uas/oauth2/accessToken?grant_type=authorization_code&client_id="
                        .\Moat::$config->linkedin->public."&redirect_uri=".$this->return_uri
                        ."&client_secret=".\Moat::$config->linkedin->secret."&code=".$code;

        $response = file_get_contents($exchange_uri);
        $params = json_decode($response, true);

        $graph_url = "https://api.linkedin.com/v1/people/~:(id)?format=json&oauth2_access_token=" . $params['access_token'];
        $in_user = json_decode(file_get_contents($graph_url));
        $user_id = $in_user->id;

        return [
            'service_user_id' => $user_id,
            'access_token' => $params['access_token'],
            'expires_at' => $params['expires'] + time() - 5
        ];
    }
}
