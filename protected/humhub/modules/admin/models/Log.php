<?php

namespace humhub\modules\admin\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property int $level
 * @property string $category
 * @property float $log_time
 * @property string $prefix
 * @property string $message
 */
class Log extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level'], 'integer'],
            [['log_time'], 'number'],
            [['prefix', 'message'], 'string'],
            [['category'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'level' => 'Level',
            'category' => 'Category',
            'log_time' => 'Log Time',
            'prefix' => 'Prefix',
            'message' => 'Message',
        ];
    }
}
