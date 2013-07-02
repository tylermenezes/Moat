<?php

namespace Moat\OAuthProviders;

/**
 * 
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 */
interface IOAuthProvider {
    public function set_return_uri($return_url);
    public function get_redirect_url();
    public function get_info($code);
}
