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
    public function actionLogin()
    {
        $password = Yii::$app->request->post('PasswordSecureForm')['password'];
        $id = Yii::$app->user->id;
        $key = UserKey::findOne(['user_id' => $id])->secret_key;
        $decrypted = Yii::$app->security->decryptByPassword(base64_decode($key), $password);
        $response = $this->fetchLoginUserOnBC($decrypted);

        if ($response->api_key) {
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'apiKey',
                'value' => $response->api_key,
                'httpOnly' => true,
                'expire' => time() + 3600 * 24 * 7, 
            ]));

            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'isLoggedFabric',
                'value' => true,
                'expire' => time() + 3600 * 24 * 7,
            ]));
            return [
                'success' => true,
                'message' => 'Login successful.',
                'user_id' => $id
            ];
        } else {
            Yii::$app->response->statusCode = 403;
            return ['error' => true, 'message' => 'Blockchain login failed.'];
        }

    }


    public function actionRegister()
    {
        $password = Yii::$app->request->post('PasswordSecureForm')['password'];
        $user_pkey = $this->fetchRegisterUserOnBC();
        $encrypted = Yii::$app->security->encryptByPassword($user_pkey->privateKey, $password);
        if($encrypted === null) {
            Yii::error("encrypted failed");
            return [
                'success' => false,
                'message' => 'Failed to register encrypted private key.',
            ];
        }

        $userKey = new UserKey();
        $userKey->user_id = Yii::$app->user->id;
        $userKey->secret_key = base64_encode($encrypted);

        if ($userKey->save()) {
            return [
                'success' => true,
                'message' => 'User key registered and encrypted successfully.',
                'data' => [
                    'user_id' => $userKey->user_id,
                    'encrypted_pkey' => base64_encode($userKey->secret_key),
                ]
            ];
        } else {
            Yii::$app->response->statusCode = 422; // Unprocessable Entity
            return [
                'success' => false,
                'message' => 'Failed to register encrypted private key.',
                'errors' => $userKey->getErrors(),
            ];
        }
    }

    private function enrollAdmin()
    {
        $response = $this->fetchEnrollAdminOnBC();
        $admin_pkey = $response->privateKey;
        // $encrypted = Yii::$app->security->encryptByPassword($admin_pkey, $password);
        // $userKey = new UserKey();
        // $userKey->user_id = Yii::$app->user->id; // hoặc ID bạn lấy theo cách riêng
        // $userKey->encrypted_pkey = $encrypted;

        // if ($userKey->save()) {
        // } else {

        // }

    }


    private function fetchRegisterUserOnBC()
    {
        $client = new Client();
        try {

            $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl('http://localhost:3000/api/ca/register')
                ->addHeaders([
                    'content-type' => 'application/json',
                ])
                ->setContent(json_encode([
                        'userId' => 'liem2hondai',
                        'affiliation' =>  "org1.department1",
                        'admin-pkey' => '-----BEGIN PRIVATE KEY-----
MIGHAgEAMBMGByqGSM49AgEGCCqGSM49AwEHBG0wawIBAQQgr2ZrxsyjEVcwkDxt
/lceepvaIGAX9dUuvJi5WoCEepGhRANCAAS695NQaWn6LZipJBTXoRQjtTgsb+Lm
7S07IsylOaIuG2xMZ4fuAeujrE49uv7wGhI1eSNXVGtJHt4q9zY0r19R
-----END PRIVATE KEY-----
'
                ]))
                ->send();
                

                

            if ($response->isOk) {
                return json_decode($response->content);
            }

            Yii::error("Failed to register user on blockchain API: " . $response->content, __METHOD__);
            return null;

        } catch (\Exception $e) {
            Yii::error("Error when calling Node.js API: " . $e->getMessage(), __METHOD__);
            return null;
        }
    }

    private function fetchEnrollAdminOnBC()
    {
        $client = new Client();
        try {
            $response = $client->createRequest()
                ->setMethod('')
                ->setUrl('http://localhost:3000/api/ca/enroll-admin')
                ->addHeaders([
                    'content-type' => 'application/json',
                    'X-Admin-Key' => $_ENV['ADMIN_API_KEY']
                ])
                ->send();

            if ($response->isOk) {
                return json_decode($response->content);
            }

            Yii::error("Failed to register user on blockchain API: " . $response->content, __METHOD__);
            return null;

        } catch (\Exception $e) {
            Yii::error("Error when calling Node.js API: " . $e->getMessage(), __METHOD__);
            return null;
        }
    }

    private function fetchLoginUserOnBC($key)
    {
        $client = new Client();
        try {
            $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl('http://localhost:3000/api/auth/login')
                ->addHeaders([
                    'content-type' => 'application/json',
                ])
                ->setContent(json_encode([
                    'userId' => Yii::$app->user->id,
                    'affiliation' =>  "org1.department1",
                    'privateKey' => $key
                ]))
                ->send();

            if ($response->isOk) {
                return json_decode($response->content);
            }

            Yii::error("Failed to register user on blockchain API: " . $response->content, __METHOD__);
            return null;

        } catch (\Exception $e) {
            Yii::error("Error when calling Node.js API: " . $e->getMessage(), __METHOD__);
            return null;
        }
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

