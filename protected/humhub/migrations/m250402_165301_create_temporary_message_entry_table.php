<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%temporary_message_entry}}`.
 */
class m250402_165301_create_temporary_message_entry_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%temporary_message_entry}}', [
            'id' => $this->primaryKey(),
            'message_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'content' => $this->string(255)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%temporary_message_entry}}');
    }
}
