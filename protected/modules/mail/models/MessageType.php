<?php

namespace humhub\modules\mail\models;

use humhub\components\ActiveRecord;
use humhub\modules\mail\Module;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\modules\user\models\User;
use Yii;
use yii\helpers\Html;

/**
 * This class represents a single conversation.
 *
 * The followings are the available columns in table 'message':
 * @property int $id
 * @property string $title
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property-read  User $originator
 * @property-read MessageEntry $lastEntry
 *
 * The followings are the available model relations:
 * @property MessageEntry[] $messageEntries
 * @property User[] $users
 *
 * @package humhub.modules.mail.models
 * @since 0.5
 */
class MessageType extends ActiveRecord
{
    public static function tableName()
    {
        return 'message_type';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            [['message_id', 'type'], 'required'],
            [['message_id'], 'integer'],
            [['type'], 'string', 'max' => 255],
        ];
    }

}
