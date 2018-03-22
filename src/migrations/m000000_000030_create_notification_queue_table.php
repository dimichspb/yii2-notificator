<?php

use yii\db\Migration;

/**
 * Handles the creation of table `notification_queue`.
 */
class m000000_000030_create_notification_queue_table extends Migration
{
    private $tableName = '{{%notification_queue%}}';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => 'char(36) PRIMARY KEY',
            'created_at' => $this->dateTime(),
            'user_id' => 'char(36)',
            'notification_id' => 'char(36)',
            'channel_class' => $this->string(255),
            'message' => $this->text(),
            'sent_at' => $this->dateTime(),
            'read_at' => $this->dateTime(),
            'attempts' => $this->text(),
            'statuses' => $this->text(),
        ]);
        if ($this->db->driverName !== 'sqlite') {
            $this->addForeignKey(
                'fk_notification_queue_notification_id',
                $this->tableName,
                'notification_id',
                '{{%notification%}}',
                'id',
                'RESTRICT',
                'CASCADE'
            );
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
