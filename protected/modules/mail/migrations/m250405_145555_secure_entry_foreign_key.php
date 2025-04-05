<?php

use humhub\components\Migration;

/**
 * Class m230919_055432_entry_foreign_key
 */
class m250405_145555_secure_entry_foreign_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('DELETE secure_message_entry FROM secure_message_entry
            LEFT JOIN user ON user.id = secure_message_entry.user_id
            LEFT JOIN message ON message.id = secure_message_entry.message_id
            WHERE user.id IS NULL OR message.id IS NULL');
        
        $this->safeAddForeignKey('fk-secure-message-entry-user-id', 'secure_message_entry', 'user_id', 'user', 'id', 'CASCADE');
        $this->safeAddForeignKey('fk-secure-message-entry-message-id', 'secure_message_entry', 'message_id', 'message', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->safeDropForeignKey('fk-secure-message-entry-user-id', 'secure_message_entry');
        $this->safeDropForeignKey('fk-secure-message-entry-message-id', 'secure_message_entry');
    }
}
