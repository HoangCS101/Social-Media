<?php

use yii\db\Migration;

class m250410_093302_add_status_to_secure_message_entry_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('secure_message_entry', 'status', $this->string()->defaultValue('pending'));
    }

    public function safeDown()
    {
        $this->dropColumn('secure_message_entry', 'status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250410_093302_add_status_to_secure_message_entry_table cannot be reverted.\n";

        return false;
    }
    */
}
