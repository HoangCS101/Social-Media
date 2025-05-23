<?php


use yii\db\Migration;

class m140507_171527_create_settings_table extends Migration
{
    public function up()
    {

        // Create New User Settings Table
        $this->createTable('space_setting', [
            'id' => 'pk',
            'space_id' => 'int(10)',
            'module_id' => 'varchar(100) DEFAULT NULL',
            'name' => 'varchar(100)',
            'value' => 'varchar(255) DEFAULT NULL',
            'created_at' => 'datetime DEFAULT NULL',
            'created_by' => 'int(11) DEFAULT NULL',
            'updated_at' => 'datetime DEFAULT NULL',
            'updated_by' => 'int(11) DEFAULT NULL',
        ], '');

        $this->createIndex('idx_space_setting', 'space_setting', 'space_id, module_id, name', true);
    }

    public function down()
    {
        echo "m140507_171527_create_settings_table does not support migration down.\n";
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
