<?php
use \Moat\Models;
use \Moat\Traits;

/**
 * Submits new deals
 *
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 */
class deals {
    use \CuteControllers\Controller;

    public function get_index()
    {
        echo \Moat::$twig->render('deals/create.html.twig');
    }

    public function post_index()
    {
        try {
            $this->require_post('company', 'url', 'details', 'redemption', 'cohort');
        } catch (\CuteControllers\HttpError $err) {
            $this->display_create(['missing_fields']);
        }

        $cohort = $this->request->post('cohort') === 'all' ? null : $this->request->post('cohort');

        new Models\Deal([
            'company' => $this->request->post('company'),
            'url' => $this->request->post('url'),
            'details' => $this->request->post('details'),
            'redemption' => $this->request->post('redemption'),
            'cohort' => $cohort
        ]);

        echo \Moat::$twig->render('deals/pending.html.twig');
    }

    protected function display_create($additional_info = [])
    {
        echo \Moat::$twig->render('deals/create.html.twig', array_merge([
            'company' => $this->request->post('company'),
            'url' => $this->request->post('url'),
            'details' => $this->request->post('details'),
            'redemption' => $this->request->post('redemption'),
            'cohort' => $this->request->post('cohort')
        ], $additional_info));
        exit;
    }
}
