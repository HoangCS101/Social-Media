<?php

namespace humhub\modules\mail\controllers;

use humhub\components\access\ControllerAccess;
use humhub\components\Controller;
use humhub\modules\file\handler\FileHandlerCollection;
use humhub\modules\mail\helpers\Url;
use humhub\modules\mail\models\forms\CreateMessage;
use humhub\modules\mail\models\forms\SecureCreateMessage;
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
class SecureController extends Controller
{
    private function getUserApiKey()
    {
        $userId = Yii::$app->user->id;
        $userKey = UserKey::find()->where(['user_id' => $userId])->one();
        return $userKey ? $userKey->api_key : null;
    }

    public function actionGetMessage()
    {
        $apiKey = $this->getUserApiKey();
        if (!$apiKey) {
            return $this->asJson(['error' => 'API key not found'])->setStatusCode(403);
        }

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://node-server.com/api/messages')
            ->setHeaders(['x-api-key' => $apiKey])
            ->send();

        return $this->asJson($response->data);
    }

    public function actionStoreMessage()
    {
        $apiKey = $this->getUserApiKey();
        if (!$apiKey) {
            return $this->asJson(['error' => 'API key not found'])->setStatusCode(403);
        }

        $requestData = Yii::$app->request->post();
        if (!isset($requestData['message_id']) || !isset($requestData['content'])) {
            return $this->asJson(['error' => 'Invalid input'])->setStatusCode(400);
        }

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('http://node-server.com/api/messages')
            ->setHeaders([
                'x-api-key' => $apiKey,
                'Content-Type' => 'application/json'
            ])
            ->setContent(json_encode([
                'message_id' => $requestData['title'],
                'content' => $requestData['content'],
                'user_id' => Yii::$app->user->id
            ]))
            ->send();

        return $this->asJson($response->data);
    }
}

