<?php
namespace dimichspb\yii\notificator\handlers;

use dimichspb\yii\notificator\interfaces\NotificationInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeInterface;
use yii\base\Event;

class BasicNotificationEventHandler extends BaseNotificationEventHandler
{
    public function handle(Event $event)
    {
        $notifications = $this->getNotificationsByEvent($event);

        foreach ($notifications as $notification) {
            $userIds = $this->filterUserIds($notification->getUserIds(), $notification->getIgnoredUserIds());
            $roleNames = $this->filterRoleNames($notification->getRoleNames(), $notification->getIgnoredRoleNames());
            foreach ($roleNames as $roleName) {
                $userIds = array_merge($userIds, $this->roleService->getUserIdsByRoleName($roleName));
            }
            $notificationType = $this->getNotificationType($notification);
            $message = $notificationType->getMessage($event);

            $this->notificationQueueRepository->create($notification, $message, $userIds);
        }
    }

    protected function filterUserIds(array $userIds, array $ignoredUserIds = [])
    {
        return $this->filterArray($userIds, $ignoredUserIds);
    }

    protected function filterRoleNames(array $roleNames, array $ignoredRoleNames = [])
    {
        return $this->filterArray($roleNames, $ignoredRoleNames);
    }

    protected function filterArray(array $array, array $exceptionList = [])
    {
        return array_filter($array, function ($item) use ($exceptionList) {
            return !in_array($item, $exceptionList);
        });
    }

    /**
     * @param NotificationInterface $notification
     * @return NotificationTypeInterface
     */
    protected function getNotificationType(NotificationInterface $notification)
    {
        $notificationTypeId = $notification->getNotificationTypeId();
        return $this->notificationTypeRepository->get($notificationTypeId);
    }
}