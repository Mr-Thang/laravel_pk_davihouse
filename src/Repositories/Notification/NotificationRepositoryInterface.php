<?php

namespace CMS\Package\Repositories\Notification;

use CMS\Package\Repositories\BaseInterface;

interface NotificationRepositoryInterface extends BaseInterface
{
    public function setupPusher();

    public function countRelationshipProject();

    public function updateMarkAsReadProject();

    public function pusherNotificationProject();
}
