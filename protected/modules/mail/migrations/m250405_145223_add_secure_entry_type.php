<?php

use humhub\components\Migration;
use humhub\modules\mail\models\AbstractSecureMessageEntry;
use humhub\modules\mail\models\SecureMessageEntry;

/**
 * Class m230213_094842_add_state
 */
class m250405_145223_add_secure_entry_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->safeAddColumn(
            AbstractSecureMessageEntry::tableName(),
            'type',
            $this->tinyInteger()
            ->defaultValue(SecureMessageEntry::type())
            ->notNull()
            ->unsigned(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->safeDropColumn(AbstractSecureMessageEntry::tableName(), 'type');
    }
}
