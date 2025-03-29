<?php

use yii\db\Migration;

class m250327_050751_add_is_love_to_vote_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%vote}}', 'is_love', $this->boolean()->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%vote}}', 'is_love');
    }
}
