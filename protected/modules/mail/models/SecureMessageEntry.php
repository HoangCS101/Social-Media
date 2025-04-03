<?php

namespace humhub\modules\mail\models;

use Yii;
use DateTime;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use humhub\modules\mail\models\Message;
use humhub\modules\user\models\User;
use humhub\modules\content\widgets\richtext\RichText;
use humhub\modules\mail\live\UserMessageDeleted;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "temporary_message_entry".
 *
 * @property int $id
 * @property int $message_id
 * @property int $user_id
 * @property string $content
 * @property string $type
 * @property string $created_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $updated_at
 *
 * @property Message $message
 * @property User $user
 */
class SecureMessageEntry extends AbstractSecureMessageEntry
{

    /*
    * @inheritdoc
    */
    public static function type(): int
    {
        return self::TYPE_MESSAGE;
    }

    /**
        * @inheritdoc
        */
    public function canEdit(?User $user = null): bool
    {
        if ($this->type !== self::type()) {
            return false;
        }

        if ($user === null) {
            if (Yii::$app->user->isGuest) {
                return false;
            }
            $user = Yii::$app->user;
        }

        return $this->created_by === $user->id;
    }

    /**
        * @inheritdoc
        */
    public function notify(bool $isNewConversation = false)
    {
        $message = new MessageEntry([
            'id' => $this->id,
            'message_id' => $this->message_id,
            'user_id' => $this->user_id,
            'content' => null,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
        ]);
        $messageNotification = new MessageNotification($this->message, $message);
        $messageNotification->isNewConversation = $isNewConversation;
        $messageNotification->notifyAll();
    }

    public function isFirstToday(): bool
    {
        $today = Yii::$app->formatter->asDatetime(new DateTime('today'), 'php:Y-m-d H:i:s');

        return !SecureMessageEntry::find()
            ->where(['message_id' => $this->message_id])
            ->andWhere(['!=', 'id', $this->id])
            ->andWhere(['>=', 'created_at', $today])
            ->exists();
    }
}

