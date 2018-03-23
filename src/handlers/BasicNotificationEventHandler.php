<?php
namespace dimichspb\yii\notificator\handlers;

use dimichspb\yii\notificator\interfaces\NotificationInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeInterface;
use dimichspb\yii\notificator\models\Notification\RoleName;
use dimichspb\yii\notificator\models\UserId;
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
                $userIds = array_merge($userIds, $this->roleService->getUserIdsByRoleName($roleName->getValue()));
            }
            $notificationType = $this->getNotificationType($notification);
            $message = $notificationType->getMessage($event);

            $this->notificationQueueService->create($notification, $message, $userIds);
        }
    }

    /**
     * @param array $userIds
     * @param array $ignoredUserIds
     * @return UserId[]
     */
    protected function filterUserIds(array $userIds, array $ignoredUserIds = [])
    {
        return $this->filterArray($userIds, $ignoredUserIds);
    }

    /**
     * @param array $roleNames
     * @param array $ignoredRoleNames
     * @return RoleName[]
     */
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
        return $this->notificationTypeService->get($notificationTypeId);
    }
}