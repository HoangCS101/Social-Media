<?php

use yii\db\Migration;

class m150430_190620_indexes extends Migration
{
    public function up()
    {
        $this->createIndex('index_secure_user_id', 'secure_message_entry', 'user_id', false);
        $this->createIndex('index_secure_message_id', 'secure_message_entry', 'message_id', false);
        $this->createIndex('index_message_type', 'message_type', 'message_id', false);
    }

    public function down()
    {
        echo "m150430_190620_indexes does not support migration down.\n";
        return false;
    }

    /*
      // Use safeUp/safeDown to do migration with transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}
