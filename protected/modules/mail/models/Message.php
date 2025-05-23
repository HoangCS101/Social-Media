<?php

namespace humhub\modules\mail\models;

use humhub\components\ActiveRecord;
use humhub\modules\mail\Module;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\modules\user\models\User;
use Yii;
use yii\helpers\Html;
use yii\httpclient\Client;
use yii\web\BadRequestHttpException;
use yii\base\Security;

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
class Message extends ActiveRecord
{
    private MessageEntry|SecureMessageEntry|null $_lastEntry = null;
    private ?int $_userCount = null;
    // public string $type = '';

    /**
     * @return string the associated database table name
     */

    // public function init() {
    //     parent::init();
    //     $this->type = $this->getType();
    // }

    public static function tableName()
    {
        return 'message';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            [['created_by', 'updated_by'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function getType()
    {
        $type = $this->hasOne(MessageType::class, ['message_id' => 'id'])->one();
        if(!$type && $this->addType('normal')) {
            return 'normal';
        }
        return $type->type;
    }

    public function getEntryUpdates($from = null)
    {
        $query = $this->type === 'secure' 
        ? $this->hasMany(SecureMessageEntry::class, ['message_id' => 'id']) 
        : $this->hasMany(MessageEntry::class, ['message_id' => 'id']);

        $query->addOrderBy(['created_at' => SORT_ASC]);

        if ($from) {
            if($this->type === 'secure') {
                $query->andWhere(['>', 'secure_message_entry.id', $from]);
            } else {
                $query->andWhere(['>', 'message_entry.id', $from]);
            }
        }

        return $query;
    }

    /**
     * @param int|null $from
     * @return MessageEntry[]|SecureMessageEntry[]
     */
    
    public function getEntryPage($from = null)
    {
        $type = $this->type;
        if ($type === 'secure') {

            $bcEntries = $this->fetchMessageFromBC();

            $query = $this->getSecureEntries();
            $query->addOrderBy(['created_at' => SORT_DESC]);
            if ($from) {
                $query->andWhere(['<', 'secure_message_entry.id', $from]);
            }

            $module = Module::getModuleInstance();
            $limit = $from ? $module->conversationUpdatePageSize : $module->conversationInitPageSize;
            $query->limit($limit);

            $dbEntries = $query->all();

            $final = [];
            $map = [];

            foreach ($bcEntries as $bcEntry) {
                $map[$bcEntry['id']] = $bcEntry;
            }

            foreach ($dbEntries as $dbEntry) {
                if($dbEntry->status === 'pending') {
                    $final[] = $dbEntry;
                    continue;
                }

                if($dbEntry->status === 'failed') {
                    if($dbEntry->user_id === Yii::$app->user->id) {
                        $final[] = $dbEntry;
                    }
                    continue;
                }

                if (!isset($map[$dbEntry->id])) {
                    $final[] = $dbEntry;
                    continue;
                }

                $bcEntry = $map[$dbEntry->id];

                if (
                    $dbEntry->message_id != $bcEntry['messageId'] ||
                    $dbEntry->user_id != $bcEntry['userId']||
                    $dbEntry->created_at != $bcEntry['createdAt']
                ) {
                    throw new BadRequestHttpException("Data mismatch at message ID {$dbEntry->id}");
                }
                if(!$dbEntry->getDecryptedContent()) {
                    $dbEntry->setDecryptedContent($dbEntry->decrypt($bcEntry['content'], $dbEntry->key));
                }
                $final[] = $dbEntry;
            }

            return array_reverse($final);

        } else {
            $query = $this->getEntries();
            $query->addOrderBy(['created_at' => SORT_DESC]);
            if ($from) {
                $query->andWhere(['<', 'message_entry.id', $from]);
            }

            $module = Module::getModuleInstance();
            $limit = $from ? $module->conversationUpdatePageSize : $module->conversationInitPageSize;
            $query->limit($limit);

            return array_reverse($query->all());
        }
    }




    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntries()
    {
        return $this->hasMany(MessageEntry::class, ['message_id' => 'id']);
    }

    public function getSecureEntries() {
        /*TODO */
        return $this->hasMany(SecureMessageEntry::class, ['message_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * @param int|null $userId
     * @return UserMessage|null
     */
    public function getUserMessage($userId = null)
    {
        if (!$userId) {
            if (Yii::$app->user->isGuest) {
                return null;
            }
            $userId = Yii::$app->user->id;
        }

        return UserMessage::findOne([
            'user_id' => $userId,
            'message_id' => $this->id,
        ]);
    }

    /**
     * @param $user
     * @return bool
     */
    public function isParticipant($user)
    {
        foreach ($this->users as $participant) {
            if ($participant->guid === $user->guid) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable('user_message', ['message_id' => 'id']);
    }

    public function getUsersCount(): int
    {
        if ($this->_userCount === null) {
            $this->_userCount = $this->getUsers()->count();
        }

        return $this->_userCount;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOriginator()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => Yii::t('MailModule.base', 'Title'),
            'created_at' => Yii::t('MailModule.base', 'Created At'),
            'created_by' => Yii::t('MailModule.base', 'Created By'),
            'updated_at' => Yii::t('MailModule.base', 'Updated At'),
            'updated_by' => Yii::t('MailModule.base', 'Updated By'),
        );
    }

    /**
     * Returns the last message of this conversation
     * @return MessageEntry|null
     */
    public function getLastEntry(): MessageEntry|SecureMessageEntry|null
    {
        if ($this->_lastEntry === null) {
            $entryClass = $this->type === 'normal' ? MessageEntry::class : SecureMessageEntry::class;
    
            $this->_lastEntry = $entryClass::find()
                ->where(['message_id' => $this->id])
                ->orderBy('created_at DESC')
                ->limit(1)
                ->one();
        }
    
        return $this->_lastEntry;
    }

    /**
     * @param bool $includeMe
     * @return \yii\web\IdentityInterface|null|User
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     */
    public function getLastActiveParticipant(bool $includeMe = false): User
    {
        if($this->type === 'secure') {
            $query = SecureMessageEntry::find()->where(['message_id' => $this->id])->orderBy('created_at DESC')->limit(1);

        }
        else {
            $query = MessageEntry::find()->where(['message_id' => $this->id])->orderBy('created_at DESC')->limit(1);

        }

        if (!$includeMe) {
            $query->andWhere(['<>', 'user_id', Yii::$app->user->id]);
        }

        $entry = $query->one();

        $user = $entry ? $entry->user : $this->getUsers()->andWhere(['<>', 'user.id', Yii::$app->user->id])->one();

        return $user ?: Yii::$app->user->getIdentity();
    }

    /**
     * Deletes message entry by given Id
     * If it's the last entry, the whole message will be deleted.
     *
     * @param MessageEntry $entry
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteEntry($entry)
    {
        if($this->type === 'secure') {
            if ($entry->message->id == $this->id) {
                if($this->getSecureEntries()->count() > 1) {
                    $entry->delete();
                } else {
                    $this->delete();
                }
            }
        }
        else {
            if ($entry->message->id == $this->id) {
                if($this->getEntries()->count() > 1) {
                    $entry->delete();
                } else {
                    $this->delete();
                }
            }
        }
        
    }

    /**
     * Mark this message as unread
     *
     * @param int|null $userId
     */
    public function markUnread($userId = null)
    {
        $userMessage = $this->getUserMessage($userId);
        if ($userMessage) {
            $userMessage->last_viewed = null;
            $userMessage->save();
        }
    }

    /**
     * Pin this message
     *
     * @param int|null $userId
     * @param bool $pin
     */
    public function pin($userId = null, bool $pin = true)
    {
        $userMessage = $this->getUserMessage($userId);
        if ($userMessage) {
            $userMessage->pinned = $pin;
            $userMessage->save();
        }
    }

    /**
     * Unpin this message
     *
     * @param int|null $userId
     */
    public function unpin($userId = null)
    {
        $this->pin($userId, false);
    }

    /**
     * User leaves a message
     *
     * If it's the last user, the whole message will be deleted.
     *
     * @param int|null $userId
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function leave($userId = null)
    {
        $userMessage = $this->getUserMessage($userId);
        if (!$userMessage) {
            return;
        }

        if (count($this->users) > 1) {
            $userMessage->delete();
        } else {
            $this->delete();
        }
    }

    /**
     * Marks a message as seen for given userId
     *
     * @param int $userId
     */
    public function seen($userId)
    {
        // Update User Message Entry
        $userMessage = UserMessage::findOne(array(
            'user_id' => $userId,
            'message_id' => $this->id,
        ));
        if ($userMessage !== null) {
            $userMessage->last_viewed = date('Y-m-d G:i:s');
            $userMessage->save();
        }
    }

    /**
     * Deletes a message, including all dependencies.
     */
    public function delete()
    {
        if($this->type === 'secure') {
            foreach (SecureMessageEntry::findAll(array('message_id' => $this->id)) as $messageEntry) {
                $messageEntry->delete();
            }
        }
        else {
            foreach (MessageEntry::findAll(array('message_id' => $this->id)) as $messageEntry) {
                $messageEntry->delete();
            }
        }
        foreach (UserMessage::findAll(array('message_id' => $this->id)) as $userMessage) {
            $userMessage->delete();
        }

        parent::delete();
    }

    /**
     * @param User $recipient
     * @param bool $originator
     * @param bool $informAfterAdd Notify about user joining with state badge
     * @return bool
     */
    public function addRecepient(User $recipient, bool $originator = false, bool $informAfterAdd = true): bool
    {
        $userMessage = new UserMessage([
            'message_id' => $this->id,
            'user_id' => $recipient->id,
            'informAfterAdd' => $informAfterAdd,
        ]);

        if ($originator) {
            $userMessage->is_originator = 1;
            $userMessage->last_viewed = date('Y-m-d G:i:s');
        }

        return $userMessage->save();

    }

    public function addType(string $type = 'normal'): bool
    {
        $messsageType = new MessageType([
            'message_id' => $this->id,
            'type' => $type
        ]);
        return $messsageType->save();
    }

    /**
     * Get users which don't want to receive messages from the current User
     *
     * @return User[]
     */
    public function getBlockers(): array
    {
        $blockerUsers = [];

        foreach ($this->users as $user) {
            if (!$user->isCurrentUser() && $user->isBlockedForUser()) {
                $blockerUsers[] = $user;
            }
        }

        return $blockerUsers;
    }

    /**
     * Get names of the users which don't want to receive messages from the current User
     *
     * @param bool Encode names
     * @return string[]
     */
    public function getBlockerNames(bool $encode = true): array
    {
        $blockerNames = [];

        foreach ($this->getBlockers() as $user) {
            $blockerName = $user->getDisplayName();
            if ($encode) {
                $blockerName = Html::encode($blockerName);
            }
            $blockerNames[] = $blockerName;
        }

        return $blockerNames;
    }

    /**
     * Check if current user cannot reply to at least one recipient of this conversation
     *
     * @return bool
     */
    public function isBlocked(): bool
    {
        foreach ($this->users as $user) {
            if (!$user->isCurrentUser() && $user->isBlockedForUser()) {
                return true;
            }
        }

        return false;
    }

    private function fetchMessageFromBC() {
        try {
            $apiKey = Yii::$app->request->cookies->getValue('apiKey');
        
            // Kiểm tra nếu apiKey không tồn tại
            if (!$apiKey) {
                Yii::error("User haven't logged in secure chat yet", __METHOD__);
                return null;
            }

            $chatboxId = $this->id; // ví dụ bạn có chatboxId từ model
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('GET')
                ->addHeaders([
                    'content-type' => 'application/json',
                    'x-api-key' => $apiKey // Thay 'your-api-key-here' bằng giá trị thực tế của bạn
                ])
                ->setUrl("http://localhost:3000/api/messages/by-message-id/{$chatboxId}")
                ->setOptions([
                    'timeout' => 1, // giây
                    'connect_timeout' => 1 // kết nối tối đa 2 giây
                ])
                ->send();

            if (!$response->isOk) {
                Yii::error("Failed to fetch message to blockchain API: " . $response->content, __METHOD__);
                throw new BadRequestHttpException('Failed to fetch messages from Node API');
            } 
            // Yii::error("Failed to post message to blockchain API: " . $response->content, __METHOD__);
            return json_decode($response->content, true);
        }
        catch (\Exception $e) {
            Yii::error("Failed to fetch message to blockchain API: ");
            // throw new BadRequestHttpException('Failed to fetch messages from Node API');
            return [];
        }


    }

    public function isPinned($userId = null): bool
    {
        $userMessage = $this->getUserMessage($userId);
        return $userMessage && $userMessage->pinned;
    }

    public function getPinIcon($userId = null): ?Icon
    {
        if ($this->isPinned($userId)) {
            return Icon::get('map-pin')
                ->tooltip(Yii::t('MailModule.base', 'Pinned'))
                ->color(Yii::$app->view->theme->variable('danger', 'red'));
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function refresh()
    {
        $this->_lastEntry = null;
        return parent::refresh();
    }
}
