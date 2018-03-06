<?php
namespace dimichspb\yii\mailqueue\models\MailQueue\search;

use dimichspb\yii\notificator\models\NotificationQueue\NotificationQueue;
use dimichspb\yii\notificator\models\NotificationQueue\Status;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class NotificationQueueSearch extends Model
{
    public $id;
    public $created_at_from;
    public $created_at_till;
    public $sent_at_from;
    public $sent_at_till;
    public $attempts_from;
    public $attempts_till;
    public $statuses;
    public $message;

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = NotificationQueue::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

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
            '>=', 'send_at', $this->send_at_from,
        ]);
        $query->andFilterWhere([
            '<=', 'send_at', $this->send_at_till,
        ]);

        $query->andFilterWhere([
            '>=', 'sent_at', $this->sent_at_from,
        ]);
        $query->andFilterWhere([
            '<=', 'sent_at', $this->sent_at_till,
        ]);

        $query->andFilterWhere([
            '>=', 'attempts', $this->attempts_from,
        ]);
        $query->andFilterWhere([
            '<=', 'attempts', $this->attempts_till
        ]);

        $query->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }

}