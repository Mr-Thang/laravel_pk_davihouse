<?php

namespace CMS\Package\Repositories\Notification;

use Carbon\Carbon;
use Pusher\Pusher;
use CMS\Package\Repositories\BaseRepository;

class NotificationRepository extends BaseRepository
{
    public function model()
    { }

    public function setupPusher()
    {
        $options = array(
            'cluster' => 'ap1',
            'encrypted' => true
        );

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        return $pusher;
    }

    public function countRelationshipProject()
    {
        return $this->_model->whereRelationshipProject()->count();
    }

    public function updateMarkAsReadProject()
    {
        $notifications = $this->_model->whereRelationshipProject()->get();
        if ($notifications) {
            $notifications->each(function ($element) {
                $element->update(['read_at' => Carbon::now()]);
            });

            $this->pusherNotificationProject();
        }
    }
    public function pusherNotificationProject()
    {
        $count = $this->countRelationshipProject() > 0 ? $this->countRelationshipProject() : '';

        $this->setupPusher()->trigger('NotificationEvent', 'send-notification', $count);
    }
}
