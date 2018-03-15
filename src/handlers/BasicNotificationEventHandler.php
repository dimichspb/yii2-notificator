<?php
namespace dimichspb\yii\notificator\handlers;

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
            $this->notificationQueueRepository->create($notification, $userIds);
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
}