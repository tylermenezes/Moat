<?php

use \Moat\Models;

/**
 * 
 * 
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes. Released under the BSD license.
 *
 */
class cron {
    use \ThinTasks\Task;

    public function main()
    {
        // Event reminders
        $upcoming_unnotified_hours = Models\OfficeHours\Slot::find()
            ->where('NOW() < starts_at')
            ->where('NOW() > DATE_SUB(starts_at, INTERVAL 1 HOUR)')
            ->where('sent_reminder = 0')
            ->where('userID IS NOT NULL')
            ->order_by('starts_at ASC')
            ->all();

        foreach ($upcoming_unnotified_hours as $slot) {
            mail($slot->user->email, 'Hours on '.date('M j', $slot->starts_at).' at '.date('g:i a', $slot->starts_at),
                \Moat::$twig->render('emails/hours_reminder.txt.twig', ['slot' => $slot]),
                'From: "Moat" <moat@studentrnd.org>');
            $slot->sent_reminder = true;
        }
    }
}
