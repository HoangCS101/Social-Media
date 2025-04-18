<?php

namespace humhub\modules\mail\controllers;

use humhub\components\access\ControllerAccess;
use humhub\components\Controller;
use humhub\modules\file\handler\FileHandlerCollection;
use humhub\modules\mail\helpers\Url;
use humhub\modules\mail\models\forms\CreateMessage;
use humhub\modules\mail\models\forms\SecureCreateMessage;
use app\jobs\CheckBlockchainStatusJob;
use humhub\modules\mail\models\forms\InviteParticipantForm;
use humhub\modules\mail\models\forms\ReplyForm;
use humhub\modules\mail\models\forms\SecureReplyForm;
use humhub\modules\mail\models\Message;
use humhub\modules\mail\models\MessageEntry;
use humhub\modules\mail\models\SecureMessageEntry;
use humhub\modules\mail\models\UserMessage;
use humhub\modules\mail\Module;
use humhub\modules\mail\permissions\SendMail;
use humhub\modules\mail\permissions\StartConversation;
use humhub\modules\mail\widgets\ConversationEntry;
use humhub\modules\mail\widgets\ConversationHeader;
use humhub\modules\mail\widgets\Messages;
use humhub\modules\User\models\User;
use humhub\modules\user\models\UserFilter;
use humhub\modules\user\models\UserPicker;
use humhub\modules\user\widgets\UserListBox;
use humhub\modules\user\models\UserKey;
use yii\httpclient\Client;
use Yii;
use yii\helpers\Html;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException; 

/**
 * MailController provides messaging actions.
 *
 * @package humhub.modules.mail.controllers
 * @since 0.5
 */
class MailController extends Controller
{
    /**
     * @inheritdoc
     */
    protected $doNotInterceptActionIds = ['get-new-message-count-json'];

    public $pageSize = 30;

    /**
     * @inheritdoc
     */
    protected function getAccessRules()
    {
        return [
            [ControllerAccess::RULE_LOGGED_IN_ONLY],
            [ControllerAccess::RULE_PERMISSION => StartConversation::class, 'actions' => ['create', 'add-user']],
        ];
    }

    /**
     * Overview of all messages
     * @param null $id
     * @return string
     */
    public function actionIndex($id = null, $type = 'normal')
    {
        if($type == 'normal') {
            return $this->render('index', [
                'messageId' => $id,
                'messageType' => $type 
            ]);
        }
        else {
            return $this->render('index', [
                'messageId' => $id,
                'messageType' => $type 
            ]);
        }
    }

    /**
     * Shows a Message Thread
     */
    public function actionShow($id, $type)
    {
        $message = ($id instanceof Message) ? $id : $this->getMessage($id);
        $this->checkMessagePermissions($message);

        // Marks message as seen
        $message->seen(Yii::$app->user->id);
        
        return $this->renderAjax('conversation', [
            'message' => $message,
            'type' => $type,
            'messageCount' => UserMessage::getNewMessageCount(),
            'replyForm' => $type === 'normal'? new ReplyForm(['model' => $message]) : new SecureReplyForm(['model' => $message]),
            'fileHandlers' => FileHandlerCollection::getByType([FileHandlerCollection::TYPE_IMPORT, FileHandlerCollection::TYPE_CREATE]),
        ]);
    }

    public function actionSeen()
    {
        $id = Yii::$app->request->post('id');

        if ($id) {
            $message = ($id instanceof Message) ? $id : $this->getMessage($id);
            $this->checkMessagePermissions($message);
            $message->seen(Yii::$app->user->id);
        }

        return $this->asJson([
            'messageCount' => UserMessage::getNewMessageCount(),
        ]);
    }

    public function actionUpdate($id, $from = null)
    {
        $message = ($id instanceof Message) ? $id : $this->getMessage($id);

        $this->checkMessagePermissions($message);

        return $this->renderAjaxContent(Messages::widget([
            'message' => $message,
            'entries' => $message->getEntryUpdates($from)->all(),
            'showDateBadge' => false,
        ]));
    }

    public function actionLoadMore($id, $from = null)
    {
        $message = ($id instanceof Message) ? $id : $this->getMessage($id);

        $this->checkMessagePermissions($message);

        $entries = $message->getEntryPage($from);

        $result = Messages::widget(['message' => $message, 'from' => $from]);

        return $this->asJson([
            'result' => $result,
            'isLast' => (count($entries) < Module::getModuleInstance()->conversationUpdatePageSize),
        ]);
    }

    public function actionReply($id, $type = 'normal')
    {
        $message = $this->getMessage($id, true);

        $this->checkMessagePermissions($message);

        // Reply Form
        if($type === 'normal') {
            $replyForm = new ReplyForm(['model' => $message]);
            if ($replyForm->load(Yii::$app->request->post()) && $replyForm->save()) {
                $reply = $replyForm->reply;
                
                return $this->asJson([
                    'secure' => false,
                    'success' => true,
                    'content' => ConversationEntry::widget(['entry' => $reply, 'showDateBadge' => $reply->isFirstToday()]),
                ]);
            }
            

            return $this->asJson([
                'success' => false,
                'error' => [
                    'message' => $replyForm->getFirstError('message'),
                ],
            ]);

        }
        else {
            $replyForm = new SecureReplyForm(['model' => $message]);
            if ($replyForm->load(Yii::$app->request->post()) && $replyForm->save()) {
                $reply = $replyForm->reply;
                return $this->asJson([
                    'secure' => true,
                    'success' => true,
                    'entryId' => $reply->id,
                    'content' => ConversationEntry::widget([ 'entry' => $reply, 'showDateBadge' => $reply->isFirstToday()]),
                ]);
            }

            return $this->asJson([
                'success' => false,
                'error' => [
                    'message' => $replyForm->getFirstError('message'),
                ],
            ]);
        }
    }


    public function actionHandleSave(string $op)
    {
        $id = Yii::$app->request->post('id');

        $reply = SecureMessageEntry::findOne(['id' => $id]);

        $success = $this->executeSaveOnBC($reply, $op);
        if($success) {
            return $this->asJson([
                'success' => true,
                'content' => ConversationEntry::widget([ 'entry' => $reply, 'showDateBadge' => $reply->isFirstToday()]),
            ]);
        }
        else {
            return $this->asJson([
                'success' => true,
                'content' => ConversationEntry::widget(['entry' => $reply, 'showDateBadge' => $reply->isFirstToday()]),
            ]);
        }   
    }


    public function actionResend($id, $type = 'secure')
    {
        $id = Yii::$app->request->post('id');

        $reply = SecureMessageEntry::findOne(['id' => $id]);

        $success = $this->executeSaveOnBC($reply, 'create');
        if($success) {
            return $this->asJson([
                'success' => true,
                'content' => ConversationEntry::widget([ 'entry' => $reply, 'showDateBadge' => $reply->isFirstToday()]),
            ]);
        }
        else {
            return $this->asJson([
                'success' => true,
                'content' => ConversationEntry::widget(['entry' => $reply, 'showDateBadge' => $reply->isFirstToday()]),
            ]);
        }   

    }

    public function actionDeleteFailure($id)
    {
        $entry = SecureMessageEntry::findOne(['id' => $id]);

        if (!$entry) {
            throw new HttpException(404);
        }

        // Check if message entry exists and it´s by this user
        if (!$entry->canEdit()) {
            throw new HttpException(403);
        }

        $entry->message->deleteEntry($entry);

        return $this->asJson([
            'success' => true,
        ]);
    }


    /**
     * @param $id
     * @return
     * @throws HttpException
     */
    public function actionUserList($id)
    {
        return $this->renderAjaxContent(UserListBox::widget([
            'query' => $this->getMessage($id, true)->getUsers(),
            'title' => '<strong>' . Yii::t('MailModule.base', 'Participants') . '</strong>',
        ]));
    }

    /**
     * Shows the invite user form
     *
     * This method invite new people to the conversation.
     */
    public function actionAddUser($id)
    {
        $message = $this->getMessage($id);

        $this->checkMessagePermissions($message);

        // Invite Form
        $inviteForm = new InviteParticipantForm(['message' => $message]);

        if ($inviteForm->load(Yii::$app->request->post())) {
            if ($inviteForm->save()) {
                return $this->asJson([
                    'result' => ConversationHeader::widget(['message' => $message]),
                ]);
            }

            return $this->asJson([
                'success' => false,
                'error' => [
                    'message' => $inviteForm->getFirstError('recipients'),
                ],
            ]);
        }

        return $this->renderAjax('adduser', ['inviteForm' => $inviteForm]);
    }

    /**
     * Overview of all messages
     * Used by MailNotificationWidget to display all recent messages
     */
    public function actionNotificationList()
    {
        $query = UserMessage::findByUser()->limit(5);
        return $this->renderAjax('notificationList', ['userMessages' => $query->all()]);
    }

    /**
     * Used by user picker, searches user which are allwed messaging permissions
     * for the current user (v1.1).
     *
     * @param null $id
     * @param $keyword
     * @return string
     * @throws HttpException
     * @throws \Throwable
     */
    public function actionSearchUser($keyword, $id = null)
    {
        $message = $this->getMessage($id);

        if ($message) {
            $this->checkMessagePermissions($message);
        }

        $result = UserPicker::filter([
            'query' => UserFilter::find()->available(),
            'keyword' => $keyword,
            'permission' => (!Yii::$app->user->isAdmin()) ? new SendMail() : null,
            'disableFillUser' => true,
            'disabledText' => Yii::t('MailModule.base', 'You are not allowed to start a conversation with this user.'),
        ]);

        // Disable already participating users
        if ($message) {
            foreach ($result as $i => $user) {
                if ($this->isParticipant($message, $user)) {
                    $index = $i++;
                    $result[$index]['disabled'] = true;
                    $result[$index]['disabledText'] = Yii::t('MailModule.base', 'This user is already participating in this conversation.');
                }
            }
        }

        return $this->asJson($result);
    }

    private function checkMessagePermissions($message ,)
    {
        if ($message == null) {
            throw new HttpException(404, 'Could not find message!');
        }

        if (!$message->isParticipant(Yii::$app->user->getIdentity())) {
            throw new HttpException(403, 'Access denied!');
        }
    }

    /**
     * Checks if a user (user json representation) is participant of a given
     * message.
     *
     * @param type $message
     * @param type $user
     * @return bool
     */
    private function isParticipant($message, $user)
    {
        foreach ($message->users as $participant) {
            if ($participant->guid === $user['guid']) {
                return true;
            }
        }
        return false;
    }

    /*
     * @deprecated
     */
    private function findUserByFilter($keyword, $maxResult)
    {
        $query = User::find()->limit($maxResult)->joinWith('profile');

        foreach (explode(" ", $keyword) as $part) {
            $query->orFilterWhere(['like', 'user.email', $part]);
            $query->orFilterWhere(['like', 'user.username', $part]);
            $query->orFilterWhere(['like', 'profile.firstname', $part]);
            $query->orFilterWhere(['like', 'profile.lastname', $part]);
            $query->orFilterWhere(['like', 'profile.title', $part]);
        }

        $query->active();

        $results = [];
        foreach ($query->all() as $user) {
            if ($user != null) {
                $userInfo = [];
                $userInfo['guid'] = $user->guid;
                $userInfo['displayName'] = Html::encode($user->displayName);
                $userInfo['image'] = $user->getProfileImage()->getUrl();
                $userInfo['link'] = $user->getUrl();
                $results[] = $userInfo;
            }
        }
        return $results;
    }

    /**
     * Creates a new Message
     * and redirects to it.
     */
    public function actionCreate($userGuid = null, ?string $title = null, ?string $message = null, ?bool $secure = false)
    {
        $model = new CreateMessage(['secure' => $secure, 'recipient' => [$userGuid], 'title' => $title, 'message' => $message]);

        // Preselect user if userGuid is given
        if ($userGuid) {
            /* @var User $user */
            $user = User::find()->where(['guid' => $userGuid])->available()->one();

            if (!$user) {
                throw new NotFoundHttpException();
            }

            if (!$user->getPermissionManager()->can(SendMail::class) && !Yii::$app->user->isAdmin()) {
                throw new ForbiddenHttpException();
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $entry = SecureMessageEntry::findOne(['message_id' => $model->messageInstance->id]);
            $this->executeSaveOnBC($entry, 'create');

            $type = $model->secure ? 'secure': 'normal';
            return $this->htmlRedirect(['index', 'id' => $model->messageInstance->id, 'type' => $type]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
            'fileHandlers' => FileHandlerCollection::getByType([FileHandlerCollection::TYPE_IMPORT, FileHandlerCollection::TYPE_CREATE]),
        ]);
    }

    /**
     * Mark a Conversation as unread
     *
     * @param int $id
     */
    public function actionMarkUnread($id)
    {
        $this->forcePostRequest();
        $this->getMessage($id, true)->markUnread();

        $nextReadMessage = $this->getNextReadMessage($id);

        return $this->asJson([
            'success' => true,
            'redirect' => $nextReadMessage ? Url::toMessenger($nextReadMessage) : Url::to(['/dashboard']),
        ]);
    }

    /**
     * Pin a Conversation
     *
     * @param int $id
     */
    public function actionPin($id)
    {
        $this->forcePostRequest();
        $message = $this->getMessage($id, true);
        $message->pin();

        return $this->asJson([
            'success' => true,
            'redirect' => Url::toMessenger($message),
        ]);
    }

    /**
     * Unpin a Conversation
     *
     * @param int $id
     */
    public function actionUnpin($id)
    {
        $this->forcePostRequest();
        $message = $this->getMessage($id, true);
        $message->unpin();

        return $this->asJson([
            'success' => true,
            'redirect' => Url::toMessenger($message),
        ]);
    }

    /**
     * Leave Message / Conversation
     *
     * Leave is only possible when at least two people are in the
     * conversation.
     * @param $id
     * @return \yii\web\Response
     * @throws HttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionLeave($id)
    {
        $this->forcePostRequest();
        $this->getMessage($id, true)->leave();

        return $this->asJson([
            'success' => true,
            'redirect' => Url::toMessenger(),
        ]);
    }

    /**
     * Edits Entry Id
     * @param $id
     * @return string|\yii\web\Response
     * @throws HttpException
     */
    public function actionEditEntry($id, $type)
    {
        $entry = $type === 'normal' ? MessageEntry::findOne(['id' => $id]) : SecureMessageEntry::findOne(['id' => $id]);

        if (!$entry) {
            throw new HttpException(404);
        }

        if (!$entry->canEdit()) {
            throw new HttpException(403);
        }

        if ($entry->load(Yii::$app->request->post())) {
            $entry->content = Yii::$app->request->post('SecureMessageEntry')['decryptedContent'];
            $entry->status = 'pending';
            $entry->save();
            $entry->fileManager->attach(Yii::$app->request->post('MessageEntry')['files'] ?? null);
            return $this->asJson([
                'success' => true,
                'content' => ConversationEntry::widget([
                    'entry' => $entry,
                    'showDateBadge' => false,
                ]),
            ]);
        }

        return $this->renderAjax('editEntry', [
            'entry' => $entry,
            'fileHandlers' => FileHandlerCollection::getByType([FileHandlerCollection::TYPE_IMPORT, FileHandlerCollection::TYPE_CREATE]),
        ]);
    }


    /**
     * Delete Entry Id
     *
     * Users can delete the own message entries.
     */
    public function actionDeleteEntry($id, $type)
    {
        $this->forcePostRequest();
        $entry = $type === 'normal' ? MessageEntry::findOne(['id' => $id]) : SecureMessageEntry::findOne(['id' => $id]);

        if (!$entry) {
            throw new HttpException(404);
        }

        // Check if message entry exists and it´s by this user
        if (!$entry->canEdit()) {
            throw new HttpException(403);
        }

        $entry->message->deleteEntry($entry);
        $this->executeSaveOnBC($entry, 'delete');
        
        return $this->asJson([
            'success' => true,
        ]);
    }

    /**
     * Returns the number of new messages as JSON
     */
    public function actionGetNewMessageCountJson()
    {
        $json = ['newMessages' => UserMessage::getNewMessageCount()];
        return $this->asJson($json);
    }


    /**
     * Returns the Message Model by given Id
     * Also an access check will be performed.
     *
     * If insufficed privileges or not found null will be returned.
     *
     * @param int $id
     * @param bool $throw
     * @return Message|null
     * @throws HttpException
     */
    private function getMessage($id, $throw = false): ?Message
    {
        $message = Message::findOne(['id' => $id]);

        if ($message && $message->getUserMessage() !== null) {
            return $message;
        }

        if ($throw) {
            throw new HttpException(404, 'Could not find message!');
        }

        return null;
    }


    private function getNextReadMessage($id): ?Message
    {
        return Message::find()
            ->leftJoin('user_message', 'user_message.message_id = message.id')
            ->where(['user_id' => Yii::$app->user->id])
            ->andWhere(['!=', 'message_id', $id])
            ->andWhere('user_message.last_viewed >= message.updated_at')
            ->orderBy([
                'user_message.pinned' => SORT_DESC,
                'message.updated_at' => SORT_DESC,
            ])
            ->one();
    }

    private function executeSaveOnBC(SecureMessageEntry $reply, $op)
    {
        $client = new Client();

        // for ($k = 0; $k < 3; $k++) {
        if($op === 'create') {
            $response = $this->setMessageEntryToBC($client, $reply);
        }

        if($op === 'update') {
            $response = $this->updateMessageEntryToBC($client, $reply);
        }

        if($op === 'delete') {
            $response = $this->deleteMessageEntryToBC($client, $reply);
        }

        if ($response && isset($response->jobId)) {
            $jobId = (int)$response->jobId;

            sleep(3); 

            $result = $this->getJobSavingInBC($client, $jobId);
            if ($result && isset($result->transactionIds) && count($result->transactionIds) > 0) {
                Yii::info("Blockchain save successful for jobId: $jobId, tx count: " . count($result->transactionIds), __METHOD__);
                $lastTxId = end($result->transactionIds);
                $success = $this->checkStatusSavingInBC($client, $lastTxId);
                if($success) {
                    $reply->content = null;
                    $reply->status = 'saved';
                    $reply->save();
                    return true;
                }
            } 
                
            // Yii::warning("Job $jobId has no transactions yet (attempt $i)", __METHOD__);

            // Yii::error("Job $jobId failed to send after 3 retries.", __METHOD__);
        } 
                
            // Yii::warning("Blockchain API did not return jobId (attempt $k)", __METHOD__);
        // }

        Yii::error("Failed to save blockchain", __METHOD__);
        $reply->status = 'failed';
        $reply->save();
        return false;
    }


    private function setMessageEntryToBC(Client $client, SecureMessageEntry $reply)
    {
        try {
            $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl('http://localhost:3000/api/messages')
                ->addHeaders([
                    'content-type' => 'application/json',
                    'x-api-key' => $_ENV['X_API_KEY'] // Thay 'your-api-key-here' bằng giá trị thực tế của bạn
                ])
                ->setContent(json_encode([
                    'id' => $reply->id,
                    'messageId' => $reply->message->id,
                    'userId' => $reply->user->id,
                    'content' => $reply->content,
                    'type' => $reply->type,
                    'createdAt' => $reply->created_at
                ]))
                ->send();

            if ($response->isOk) {
                return json_decode($response->content);
            }

            Yii::error("Failed to post message to blockchain API: " . $response->content, __METHOD__);
            return null;

        } catch (\Exception $e) {
            Yii::error("Error when calling Node.js API: " . $e->getMessage(), __METHOD__);
            return null;
        }
    }

    private function updateMessageEntryToBC(Client $client, SecureMessageEntry $reply, $modify = false)
    {
        try {
            $response = $client->createRequest()
                ->setMethod('PUT')
                ->setUrl("http://localhost:3000/api/messages/{$reply->id}")
                ->addHeaders([
                    'content-type' => 'application/json',
                    'x-api-key' => $_ENV['X_API_KEY'] // Thay 'your-api-key-here' bằng giá trị thực tế của bạn
                ])
                ->setContent(json_encode([
                    'id' => $reply->id,
                    'messageId' => $reply->message->id,
                    'userId' => $reply->user->id,
                    'content' => $reply->content,
                    'type' => $reply->type,
                    'createdAt' => $reply->created_at
                ]))
                ->send();

            if ($response->isOk) {
                return json_decode($response->content);
            } else {
                Yii::error("Failed to update message to blockchain API: " . $response->content, __METHOD__);
                return null;
            }
        } catch (\Exception $e) {
            Yii::error("Error when calling Node.js API: " . $e->getMessage(), __METHOD__);
            return null;
        }
    }

    private function deleteMessageEntryToBC(Client $client, SecureMessageEntry $reply, $modify = false)
    {
        try {
            $response = $client->createRequest()
                ->setMethod('DELETE')
                ->setUrl("http://localhost:3000/api/messages/{$reply->id}")
                ->addHeaders([
                    'content-type' => 'application/json',
                    'x-api-key' => $_ENV['X_API_KEY'] // Thay 'your-api-key-here' bằng giá trị thực tế của bạn
                ])
                ->send();

            if ($response->isOk) {
                return json_decode($response->content);
            } else {
                Yii::error("Failed to delete message on blockchain API: " . $response->content, __METHOD__);
                return null;
            }
        } catch (\Exception $e) {
            Yii::error("Error when calling Node.js API: " . $e->getMessage(), __METHOD__);
            return null;
        }
    }

    private function getJobSavingInBC(Client $client, int $jobId)
    {
        try {
            $response = $client->createRequest()
                ->setMethod('GET')
                ->addHeaders([
                    'content-type' => 'application/json',
                    'x-api-key' => $_ENV['X_API_KEY'] // Thay 'your-api-key-here' bằng giá trị thực tế của bạn
                ])
                ->setUrl("http://localhost:3000/api/jobs/{$jobId}")
                ->send();

            if ($response->isOk) {
                return json_decode($response->content);

            } 

            Yii::warning("Status check failed for jobId $jobId: " . $response->content, __METHOD__);
            return null;

        } catch (\Exception $e) {
            Yii::error("Error when checking status of job $jobId: " . $e->getMessage(), __METHOD__);
            return null;
        }
    }

    private function checkStatusSavingInBC(Client $client, string $transactionId)
    {
        try {
            $response = $client->createRequest()
                ->setMethod('GET')
                ->addHeaders([
                    'content-type' => 'application/json',
                    'x-api-key' => $_ENV['X_API_KEY'] // Thay 'your-api-key-here' bằng giá trị thực tế của bạn
                ])
                ->setUrl("http://localhost:3000/api/transactions/{$transactionId}")
                ->send();

            if ($response->isOk) {
                $content = json_decode($response->content);
                if($content && $content->validationCode && $content->validationCode === 'VALID') {
                    return true;
                }
            } 

            Yii::warning("Status check failed for jobId $transactionId: " . $response->content, __METHOD__);
            return false;

        } catch (\Exception $e) {
            Yii::error("Error when checking status of job $transactionId: " . $e->getMessage(), __METHOD__);
            return false;
        }
    }

    
}
