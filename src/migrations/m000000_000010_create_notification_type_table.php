<?php

use yii\db\Migration;

/**
 * Handles the creation of table `notification`.
 */
class m000000_000010_create_notification_type_table extends Migration
{
    private $tableName = '{{%notification_type%}}';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => 'char(36)',
            'created_at' => $this->dateTime(),
            'created_by' => 'char(36)',
            'notification_type_class' => $this->string(255),
            'events' => $this->text(),
            'params' => $this->text(),
            'statuses' => $this->text(),
        ]);
        if ($this->db->driverName !== 'sqlite') {
            $this->addPrimaryKey('pk_notification_type', $this->tableName, 'id');
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
