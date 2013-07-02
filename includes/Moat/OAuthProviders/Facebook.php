<?php

namespace Moat\OAuthProviders;

/**
 * 
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 */
class Facebook implements IOAuthProvider {
    protected $secret;

    public function set_return_uri($new)
    {
        $this->return_uri = $new;
    }

    public function get_redirect_url()
    {
        return "https://www.facebook.com/dialog/oauth?client_id=".\Moat::$config->facebook->public."&redirect_uri=".$this->return_uri;
    }

    public function get_info($code)
    {
        if ($code === null) {
            throw new OAuthDeniedException();
        }

        $exchange_uri = "https://graph.facebook.com/oauth/access_token?client_id="
                        .\Moat::$config->facebook->public."&redirect_uri=".$this->return_uri
                        ."&client_secret=".\Moat::$config->facebook->secret."&code=".$code;

        $response = file_get_contents($exchange_uri);
        $params = null;
        parse_str($response, $params);

        $graph_url = "https://graph.facebook.com/me?access_token=" . $params['access_token'];
        $fb_user = json_decode(file_get_contents($graph_url));
        $user_id = $fb_user->id;

        return [
            'service_user_id' => $user_id,
            'access_token' => $params['access_token'],
            'expires_at' => $params['expires'] + time() - 5
        ];
    }
}
