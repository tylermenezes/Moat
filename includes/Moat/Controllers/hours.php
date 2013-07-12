<?php
use \Moat\Models;
use \Moat\Models\OfficeHours;
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
    use Traits\NeedsCohort;

    public function action_index()
    {
        $upcoming_hours = OfficeHours\Block::find()->order_by('starts_at ASC');

        if (!$this->request->get('all')) {
            $upcoming_hours = $upcoming_hours->where('NOW() < DATE_ADD(starts_at, INTERVAL 1 DAY)');
        }

        $upcoming_hours = $upcoming_hours->all();
        echo \Moat::$twig->render('hours/view.html.twig', ['upcoming_blocks' => $upcoming_hours]);
    }

    public function action_index_ics()
    {
        header("Content-Type: text/Calendar");
        header("Content-Disposition: inline; filename=ical.ics");
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        $upcoming_hours = OfficeHours\Block::find()->where('userID = ?', Models\User::me()->id)->order_by('starts_at ASC')->all();
        echo \Moat::$twig->render('hours/calendar.ics.twig', ['upcoming_blocks' => $upcoming_hours]);
    }

    public function get_book($slotID)
    {
        try {
            $slot = OfficeHours\Slot::one($slotID);
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(404);
        }

        $slot->userID = Models\User::me()->id;
        $slot->update();
        $this->redirect('/hours');
    }

    public function get_release($slotID)
    {
        try {
            $slot = OfficeHours\Slot::one($slotID);
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(404);
        }

        if ($slot->user->id !== Models\User::me()->id) {
            throw new \CuteControllers\HttpError(403);
        }

        $slot->userID = null;
        $slot->sent_reminder = false;
        $slot->update();
        $this->redirect('/hours');
    }

    public function get_noshow($slotID)
    {
        try {
            $slot = OfficeHours\Slot::one($slotID);
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(404);
        }

        if (!Models\User::me()->is_admin && !$slot->block->user->id !== Models\User::me()->id) {
            throw new \CuteControllers\HttpError(403);
        }

        mail($slot->user->email, 'No-show for office hours',
            \Moat::$twig->render('emails/hours_noshow.txt.twig', ['slot' => $slot]),
            'From: "Moat" <moat@studentrnd.org>');

        $slot->noshow = true;
        $slot->update();
        $this->redirect('/hours');
    }

    public function get_create()
    {
        if (!Models\User::me()->is_adviser) {
            throw new \CuteControllers\HttpError(403);
        }

        echo \Moat::$twig->render('hours/create.html.twig');
    }

    public function post_create()
    {
        if (!Models\User::me()->is_adviser) {
            throw new \CuteControllers\HttpError(403);
        }

        $this->require_post('starts_at', 'length', 'number');
        $starts_at = strtotime($this->request->post('starts_at'));
        $length = $this->request->post('length');
        $number = $this->request->post('number');
        $description = $this->request->post('description');

        $block = new OfficeHours\Block([
            'userID' => Models\User::me()->id,
            'starts_at' => $starts_at,
            'description' => $description
        ]);

        $time = $starts_at;
        for ($i = 0; $i < $number; $i++) {
            $slot = new OfficeHours\Slot([
                'blockID' => $block->id,
                'starts_at' => $time,
                'ends_at' => $time + ($length * 60)
            ]);
            $time += $length * 60;
        }

        $this->redirect('/hours');
    }

    public function get_cancel($type, $id)
    {

        try {
            if ($type === 'slot') {
                $obj = OfficeHours\Slot::one($id);
                if (!Models\User::me()->is_admin && !$obj->block->user->id !== Models\User::me()->id) {
                    throw new \CuteControllers\HttpError(403);
                }

                // TODO: send emails
            } else if ($type === 'block') {
                $obj = OfficeHours\Block::one($id);
                if (!Models\User::me()->is_admin && !$obj->user->id !== Models\User::me()->id) {
                    throw new \CuteControllers\HttpError(403);
                }

                // TODO: send emails
            } else {
                throw new \CuteControllers\HttpError(404);
            }
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(404);
        }

        $obj->delete();
        $this->redirect('/hours');
    }
}
