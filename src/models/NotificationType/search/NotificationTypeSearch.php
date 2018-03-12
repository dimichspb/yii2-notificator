<?php
namespace dimichspb\yii\notificator\models\NotificationType\search;

use dimichspb\yii\notificator\models\Notification\Status;
use dimichspb\yii\notificator\models\Notification\Notification;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class NotificationTypeSearch extends Model
{
    public $id;
    public $created_at_from;
    public $created_at_till;
    public $created_by;
    public $notification_type_class;
    public $events;
    public $statuses;

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Notification::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere([
            '>=', 'created_at', $this->created_at_from,
        ]);
        $query->andFilterWhere([
            '<=', 'created_at', $this->created_at_till,
        ]);

        $query->andFilterWhere([
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['in', 'notification_type_class', $this->notification_type_class]);

        return $dataProvider;
    }

}