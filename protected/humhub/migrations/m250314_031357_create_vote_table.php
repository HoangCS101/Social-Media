<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vote}}`.
 */
class m250314_031357_create_vote_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vote}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'forum_id' => $this->integer()->notNull(),
            'is_vote' => $this->boolean()->notNull()->defaultValue(0),
            'value_vote' => $this->integer()->notNull()->defaultValue(0),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%vote}}');
    }
}
