<?php
namespace dimichspb\yii\notificator\models\Notification\search;

use dimichspb\yii\notificator\models\Notification\Status;
use dimichspb\yii\notificator\models\Notification\Notification;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class NotificationSearch extends Model
{
    public $id;
    public $created_at_from;
    public $created_at_till;
    public $created_by;
    public $user_id;
    public $channel_class;
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
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere([
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'channel_class', $this->channel_class]);

        return $dataProvider;
    }

}