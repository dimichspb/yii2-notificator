<?php

use yii\db\Migration;

/**
 * Handles the creation of table `notification`.
 */
class m000000_000000_create_notification_table extends Migration
{
    private $tableName = '{{%notification%}}';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => 'char(36)',
            'created_at' => $this->dateTime(),
            'created_by' => 'char(36)',
            'user_id' => 'char(36)',
            'channel_class' => $this->string(255),
            'notification_type_class' => $this->string(255),
            'statuses' => $this->text(),
        ]);
        if ($this->db->driverName !== 'sqlite') {
            $this->addPrimaryKey('pk_mail_queue', $this->tableName, 'id');
        }
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
