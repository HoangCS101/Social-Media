<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%secure_message_entry}}`.
 */
class m250403_090631_create_secure_message_entry_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%secure_message_entry}}', [
            'id' => $this->primaryKey(),
            'message_id' => $this->integer(10)->notNull(),
            'user_id' => $this->integer(10)->notNull(),
            'type' => $this->integer()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_by' => $this->integer(10)->notNull(),
            'updated_by' => $this->integer(10)->notNull(),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        
        $this->addForeignKey(
            'fk-secure_message_entry-message_id',
            '{{%secure_message_entry}}',
            'message_id',
            '{{%message}}',
            'id',
            'CASCADE'
        );
    
        $this->addForeignKey(
            'fk-secure_message_entry-user_id',
            '{{%secure_message_entry}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-secure_message_entry-message_id', '{{%secure_message_entry}}');
        $this->dropForeignKey('fk-secure_message_entry-user_id', '{{%secure_message_entry}}');
        
        $this->dropTable('{{%secure_message_entry}}');
    }
}
