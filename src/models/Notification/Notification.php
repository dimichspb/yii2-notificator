<?php
namespace dimichspb\yii\notificator\models\Notification;

use dimichspb\yii\notificator\EventTrait;
use dimichspb\yii\notificator\interfaces\MessageInterface;
use dimichspb\yii\notificator\interfaces\NotificationInterface;
use dimichspb\yii\notificator\models\InstantiateTrait;
use dimichspb\yii\notificator\models\Notification\events\CreatedAtUpdatedEvent;
use dimichspb\yii\notificator\models\Notification\events\CreatedByUpdatedEvent;
use dimichspb\yii\notificator\models\Notification\events\IgnoredRoleNamesUpdatedEvent;
use dimichspb\yii\notificator\models\Notification\events\IgnoredUserIdsUpdatedEvent;
use dimichspb\yii\notificator\models\Notification\events\RoleNamesUpdatedEvent;
use dimichspb\yii\notificator\models\Notification\events\StatusAddedEvent;
use dimichspb\yii\notificator\models\Notification\events\ChannelClassUpdatedEvent;
use dimichspb\yii\notificator\models\Notification\events\NotificationTypeIdUpdatedEvent;
use dimichspb\yii\notificator\models\Notification\events\UserIdsUpdatedEvent;
use dimichspb\yii\notificator\models\NotificationType\Id as NotificationTypeId;
use dimichspb\yii\notificator\models\NotificationType\NotificationType;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * Class Notification
 * @package dimichspb\yii\notificator\models\Notification
 *
 * @property NotificationType $notificationType
 */
class Notification extends ActiveRecord implements NotificationInterface
{
    use EventTrait, InstantiateTrait;

    /**
     * @var Id
     */
    protected $id;

    /**
     * @var CreatedAt
     */
    protected $created_at;

    /**
     * @var CreatedBy
     */
    protected $created_by;

    /**
     * @var NotificationTypeId
     */
    protected $notification_type_id;

    /**
     * @var ChannelClass
     */
    protected $channel_class;
    
    /**
     * @var UserId[]
     */
    protected $user_ids;

    /**
     * @var RoleName[]
     */
    protected $role_names;

    /**
     * @var UserId[]
     */
    protected $ignored_user_ids;

    /**
     * @var RoleName[]
     */
    protected $ignored_role_names;
    
    /**
     * @var Status[]
     */
    protected $statuses;


    public function __construct(
        NotificationTypeId $notificationTypeId, 
        ChannelClass $channelClass,
        array $userIds = [],
        array $roleNames = [],
        array $ignoredUserIds = [],
        array $ignoredRoleNames = [],
        CreatedBy $createdBy = null, 
        array $config = []
    ) {
        $this->id = new Id();
        $this->created_at = new CreatedAt();
        $this->notification_type_id = $notificationTypeId;
        $this->channel_class = $channelClass;
        $this->user_ids = $userIds;
        $this->role_names = $roleNames;
        $this->ignored_user_ids = $ignoredUserIds;
        $this->ignored_role_names = $ignoredRoleNames;

        $this->created_by = $createdBy;
        
        $this->statuses[] = new Status(Status::STATUS_ACTIVE);

        parent::__construct($config);
    }

    public function getId()
    {
        return $this->id;
    }

    protected function setNotificationTypeId(NotificationTypeId $notificationTypeId)
    {
        $this->notification_type_id = $notificationTypeId;
        $this->recordEvent(new NotificationTypeIdUpdatedEvent());

        return $this;
    }

    public function getNotificationTypeId()
    {
        return $this->notification_type_id;
    }

    protected function setChannelClass(ChannelClass $channelClass)
    {
        $this->channel_class = $channelClass;
        $this->recordEvent(new ChannelClassUpdatedEvent());

        return $this->channel_class;
    }

    public function getChannelClass()
    {
        return $this->channel_class;
    }

    protected function setCreatedAt(CreatedAt $createdAt)
    {
        $this->created_at = $createdAt;
        $this->recordEvent(new CreatedAtUpdatedEvent());

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    protected function setCreatedBy(CreatedBy $createdBy)
    {
        $this->created_by = $createdBy;
        $this->recordEvent(new CreatedByUpdatedEvent());

        return $this;
    }

    public function getCreatedBy()
    {
        return $this->created_by;
    }
    
    protected function setUserIds(array $userIds = [])
    {
        $this->user_ids = $userIds;
        $this->recordEvent(new UserIdsUpdatedEvent());
        
        return $this;
    }

    protected function addUserId($userId)
    {
        $this->user_ids[] = $userId;
        $this->recordEvent(new UserIdsUpdatedEvent());
        
        return $this;
    }
    
    public function getUserIds()
    {
        return $this->user_ids;
    }

    protected function setRoleNames(array $roleNames = [])
    {
        $this->role_names = $roleNames;
        $this->recordEvent(new RoleNamesUpdatedEvent());
        
        return $this;
    }
    
    protected function addRoleName($roleName)
    {
        $this->role_names[] = $roleName;
        $this->recordEvent(new RoleNamesUpdatedEvent());
        
        return $this;
    }
    
    public function getRoleNames()
    {
        return $this->role_names;
    }

    protected function setIgnoredUserIds(array $ignoredUserIds = [])
    {
        $this->ignored_user_ids = $ignoredUserIds;
        $this->recordEvent(new IgnoredUserIdsUpdatedEvent());

        return $this;
    }

    protected function addIgnoredUserId($userId)
    {
        $this->ignored_user_ids[] = $userId;
        $this->recordEvent(new IgnoredUserIdsUpdatedEvent());

        return $this;
    }

    public function getIgnoredUserIds()
    {
        return $this->ignored_user_ids;
    }

    protected function setIgnoredRoleNames(array $ignoredRoleNames = [])
    {
        $this->ignored_role_names = $ignoredRoleNames;
        $this->recordEvent(new IgnoredRoleNamesUpdatedEvent());

        return $this;
    }

    protected function addIgnoredRoleName($ignoredRoleName)
    {
        $this->ignored_role_names[] = $ignoredRoleName;
        $this->recordEvent(new IgnoredRoleNamesUpdatedEvent());

        return $this;
    }

    public function getIgnoredRoleNames()
    {
        return $this->ignored_role_names;
    }
    
    public function addStatus(Status $status)
    {
        $this->statuses[] = $status;
        $this->save();
        $this->recordEvent(new StatusAddedEvent());
    }

    public function getLastStatus()
    {
        return end($this->statuses);
    }

    public function getMessage()
    {
        
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notification%}}';
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function afterFind()
    {
        $this->id = new Id(
            $this->getAttribute('id')
        );
        $this->user_ids = array_map(function ($row) {
            return new UserId(
                $row['value']
            );
        }, Json::decode($this->getAttribute('user_ids')));

        $this->role_names = array_map(function ($row) {
            return new RoleName(
                $row['value']
            );
        }, Json::decode($this->getAttribute('role_names')));

        $this->ignored_user_ids = array_map(function ($row) {
            return new UserId(
                $row['value']
            );
        }, Json::decode($this->getAttribute('ignored_user_ids')));

        $this->ignored_role_names = array_map(function ($row) {
            return new RoleName(
                $row['value']
            );
        }, Json::decode($this->getAttribute('ignored_role_names')));

        $this->created_at = new CreatedAt(
            $this->getAttribute('created_at')
        );
        $this->created_by = new CreatedBy(
            $this->getAttribute('created_by')
        );
        $this->channel_class = new ChannelClass(
            $this->getAttribute('channel_class')
        );
        $this->notification_type_id = new NotificationTypeId(
            $this->getAttribute('notification_type_id')
        );
        $this->statuses = array_map(function ($row) {
            return new Status(
                $row['value'],
                new CreatedAt($row['created_at'])
            );
        }, Json::decode($this->getAttribute('statuses')));

        parent::afterFind();
    }

    public function beforeSave($insert)
    {
        $this->setAttribute('id', $this->id->getValue());
        $this->setAttribute('user_ids', Json::encode(array_map(function (UserId $userId) {
            return [
                'value' => $userId->getValue(),
            ];
        }, $this->user_ids)));
        $this->setAttribute('role_names', Json::encode(array_map(function (RoleName $roleName) {
            return [
                'value' => $roleName->getValue(),
            ];
        }, $this->role_names)));
        $this->setAttribute('ignored_user_ids', Json::encode(array_map(function (UserId $userId) {
            return [
                'value' => $userId->getValue(),
            ];
        }, $this->ignored_user_ids)));
        $this->setAttribute('ignored_role_names', Json::encode(array_map(function (RoleName $roleName) {
            return [
                'value' => $roleName->getValue(),
            ];
        }, $this->ignored_role_names)));
        $this->setAttribute('created_at', $this->created_at->getValue());
        $this->setAttribute('created_by', $this->created_by->getValue());
        $this->setAttribute('channel_class', $this->channel_class->getValue());
        $this->setAttribute('notification_type_id', $this->notification_type_id->getValue());
        $this->setAttribute('statuses', Json::encode(array_map(function (Status $status) {
            return [
                'value' => $status->getValue(),
                'created_at' => $status->getCreatedAt()->getValue(),
            ];
        }, $this->statuses)));

        return parent::beforeSave($insert);
    }

}