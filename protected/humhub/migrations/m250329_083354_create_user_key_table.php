<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_key}}`.
 */
class m250329_083354_create_user_key_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('user_key', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->unique(),
            'secret_key' => $this->text()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Tạo khóa ngoại liên kết với bảng user
        // $this->addForeignKey(
        //     'fk-user_key-user_id',
        //     'user_key',
        //     'user_id',
        //     'user',
        //     'id',
        //     'CASCADE'
        // );
    }

    public function safeDown()
    {
        // $this->dropForeignKey('fk-user_key-user_id', 'user_key');
        $this->dropTable('user_key');
    }
}

