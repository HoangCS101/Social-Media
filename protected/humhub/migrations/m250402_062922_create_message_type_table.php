<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%message_type}}`.
 */
class m250402_062922_create_message_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%message_type}}', [
            'id' => $this->primaryKey(),
            'message_id'=> $this->integer(),
            'type' => $this->string(255)->notNull()
        ]);

        $this->addForeignKey(
            'fk-message-type-type',
            'message_type',
            'message_id',
            'message',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-message-type-type', 'message_type');
        $this->dropTable('{{%message_type}}');
    }
}
