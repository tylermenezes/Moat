<?php
use \Moat\Models;
use \Moat\Traits;

/**
 * Tracks available deals
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 */
class index {
    use \CuteControllers\Controller;
    use Traits\NeedsCohort;

    public function get_index()
    {
        echo \Moat::$twig->render('deals/view.html.twig', ['deals' => Models\Cohort::current()->get_deals(!Models\User::me()->is_admin)]);
    }

    public function get_moderate($deal)
    {
        if (!Models\User::me()->is_admin) {
            throw new \CuteControllers\HttpError(403);
        }

        $this->require_get('approved');

        $deal = Models\Deal::one($deal);

        if ($this->request->get('approved') === 'delete') {
            $deal->delete();
        } else {
            $deal->approved = $this->request->get('approved') === 'yes';
            $deal->update();
        }

        $this->redirect('/deals');
    }
}
