<?php

use yii\db\Migration;

class m131024_165510_initial extends Migration
{
    public function up()
    {

        $this->createTable('message_type', [
            'id' => 'pk',
            'message_id' => 'int(11) NOT NULL',
            'type' => 'varchar(255) NOT NULL'
        ], '');

        $this->createTable('secure_message_entry', [
            'id' => 'pk',
            'message_id' => 'int(11) NOT NULL',
            'user_id' => 'int(11) NOT NULL',
            'file_id' => 'int(11) DEFAULT NULL',
            'created_at' => 'datetime DEFAULT NULL',
            'created_by' => 'int(11) DEFAULT NULL',
            'updated_at' => 'datetime DEFAULT NULL',
            'updated_by' => 'int(11) DEFAULT NULL',
        ], '');
    }

    public function down()
    {
        echo "m131024_165510_initial does not support migration down.\n";
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
