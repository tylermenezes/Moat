<?php
use \Moat\Models;
use \Moat\Traits;

/**
 * Edits and displays company profiles
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 */
class company {
    use \CuteControllers\Controller;
    use Traits\NeedsLogin;
    use Traits\NeedsCohort;

    public function get_index($companyID)
    {
        try {
            $company = Models\Company::one($companyID);
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(404);
        }

        echo \Moat::$twig->render('company/view.html.twig', ['company' => $company]);
    }

    public function get_new()
    {
        echo \Moat::$twig->render('company/new.html.twig');
    }

    public function post_new()
    {
        if (!Models\User::me()->is_admin) {
            throw new \CuteControllers\HttpError(403);
        }

        $this->require_post('name', 'description');
        $company = new Models\Company([
            'cohortID' => Models\Cohort::current()->id,
            'name' => $this->request->post('name'),
            'description' => $this->request->post('description'),
            'is_admin' => $this->request->post('is_admin'),
            'is_adviser' => $this->request->post('is_adviser')
        ]);
        $this->redirect('/company/'.$company->id);
    }

    public function get_edit($companyID)
    {
        try {
            $company = Models\Company::one($companyID);
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(404);
        }

        echo \Moat::$twig->render('company/edit.html.twig', ['company' => $company]);
    }

    public function post_edit($companyID)
    {
        try {
            $company = Models\Company::one($companyID);
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(404);
        }

        $this->require_post('name', 'description');

        $company->name = $this->request->post('name');
        $company->description = $this->request->post('description');

        if (Models\User::me()->is_admin) {
            $company->is_admin = $this->request->post('is_admin') ? true : false;
            $company->is_adviser = $this->request->post('is_adviser') ? true : false;
        }

        $company->update();
        $this->redirect('/company/'.$company->id);
    }
}
