<?php

namespace humhub\modules\mail\models;

use Yii;
use yii\db\ActiveRecord;
use humhub\modules\user\models\User;

/**
 * This is the model class for table "request".
 *
 * @property int $id
 * @property int $sender_id
 * @property int $receiver_id
 * @property string $content
 *
 * @property User $sender
 * @property User $receiver
 */
class Request extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%request}}';
    }

    public function rules()
    {
        return [
            [['sender_id', 'receiver_id', 'content'], 'required'],
            [['sender_id', 'receiver_id'], 'integer'],
            [['content'], 'string'],
            // [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['sender_id' => 'id']],
            // [['receiver_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['receiver_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'sender_id' => 'Sender',
            'receiver_id' => 'Receiver',
            'content' => 'Content',
        ];
    }

    public function getSender()
    {
        return $this->hasOne(User::class, ['id' => 'sender_id']);
    }

    public function getReceiver()
    {
        return $this->hasOne(User::class, ['id' => 'receiver_id']);
    }
}
