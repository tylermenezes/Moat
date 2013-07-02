<?php
use \Moat\Models;
use \Moat\Traits;

/**
 * Allows for creation, booking, and cancellation of office hours.
 *
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 */
class hours {
    use \CuteControllers\Controller;
    use Traits\NeedsLogin;

    public function action_index()
    {
        echo \Moat::$twig->render('hours/view.html.twig');
    }

    public function post_book()
    {
        // TODO
    }

    public function post_release()
    {
        // TODO
    }

    public function get_create()
    {
        echo \Moat::$twig->render('hours/create.html.twig');
    }

    public function post_create()
    {
        // TODO
    }

    public function post_cancel()
    {
        // TODO
    }
}
