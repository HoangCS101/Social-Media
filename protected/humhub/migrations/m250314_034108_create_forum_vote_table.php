<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%forum_vote}}`.
 */
class m250314_034108_create_forum_vote_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%forum_vote}}', [
            'id' => $this->primaryKey(),
            'forum_id' => $this->integer()->notNull(),
            'total_vote' => $this->integer()->notNull()->defaultValue(0),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%forum_vote}}');
    }
}
