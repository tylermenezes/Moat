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
class oauth {
    use \CuteControllers\Controller;

    public function before_get_oauth()
    {
        $url_params = explode('/', $this->routing_information->unmatched_path);
        $network = $url_params[1];

        $providers = [
            'facebook' => '\\Moat\\OAuthProviders\\Facebook',
            'twitter' => '\\Moat\\OAuthProviders\\Twitter',
            'linkedin' => '\\Moat\\OAuthProviders\\LinkedIn'
        ];
        $provider_name = $providers[$network];
        $this->provider = new $provider_name();
        $this->provider->set_return_uri(\CuteControllers\Router::link('/user/oauth/return/'.$network, true));
    }

    public function get_unlink($network)
    {
        if (Models\User::me()->get_oauth($network) !== null) {
            Models\User::me()->get_oauth($network)->delete();
        }

        $this->redirect('/user/link');
    }

    public function get_start($network)
    {
        $this->redirect($this->provider->get_redirect_url());
    }

    public function get_return($network)
    {
        if (Models\User::me()->get_oauth($network) !== null) {
            Models\User::me()->get_oauth($network)->delete();
        }

        $info = $this->provider->get_info($this->request->get('code'));

        new Models\OAuth([
            'userID' => Models\User::me()->id,
            'service' => $network,
            'service_user_id' => $info['service_user_id'],
            'access_token' => $info['access_token'],
            'expires_at' => $info['expires_at']
        ]);

        $this->redirect('/user/link');
    }
}
