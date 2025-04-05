<?php

use humhub\components\Migration;
use humhub\modules\mail\models\SecureMessageEntry;

/**
 * Class m230214_062338_drop_file_id
 */
class m250405_145520_drop_file_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->safeDropColumn(SecureMessageEntry::tableName(), 'file_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230214_062338_drop_file_id cannot be reverted.\n";

        return false;
    }
}
