<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%request}}`.
 */
class m250425_041736_create_request_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%request}}', [
            'id' => $this->primaryKey(),
            'sender_id' => $this->integer()->notNull(),
            'receiver_id' => $this->integer()->notNull(),
            'content'  => $this->text()->notNull(),
            'status' => $this->string()->notNull()->defaultValue('pending')
        ]);
        $this->addForeignKey('fk_request_sender', 'request', 'sender_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('fk_request_receiver', 'request', 'receiver_id', 'user', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_request_sender',      
            'request'               
        );
        $this->dropForeignKey(
            'fk_request_receiver',      
            'request'               
        );
        $this->dropTable('{{%request}}');
    }
}
