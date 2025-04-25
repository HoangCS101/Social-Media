<?php

use yii\db\Migration;

class m250408_103419_add_content_and_key_to_secure_message_entry extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('secure_message_entry', 'content', $this->string()->defaultValue(null));
        $this->addColumn('secure_message_entry', 'key', $this->string()->defaultValue(null));
    }

    public function safeDown()
    {
        $this->dropColumn('secure_message_entry', 'key');
        $this->dropColumn('secure_message_entry', 'content');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250408_103419_add_content_and_key_to_secure_message_entry cannot be reverted.\n";

        return false;
    }
    */
}
