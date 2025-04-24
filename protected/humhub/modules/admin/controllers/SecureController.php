<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\admin\controllers;
use humhub\modules\user\models\UserKey;
use humhub\modules\admin\components\Controller;
use humhub\modules\admin\permissions\ManageSettings;
use humhub\modules\admin\permissions\ManageGroups;
use humhub\modules\admin\permissions\ManageUsers;
use humhub\modules\mail\helpers\Url;
use yii\httpclient\Client;
use yii;

/**
 * User management
 *
 * @since 0.5
 */
class SecureController extends Controller
{
    protected function getAccessRules()
    {
        return [
            ['permissions' => [ManageUsers::class, ManageSettings::class]]
        ];
    }

    public function actionRegister($userId, $password)
    {
        $key = Yii::$app->request->cookies->getValue('adminPrivateKey');
        if(!$key) {
            $encodedKey = $this->actionEnrollAdmin();
            $key = base64_decode($encodedKey);

        }
        $user_pkey = $this->fetchRegisterUserOnBC($key, $userId);
        if(!$user_pkey) {
            Yii::error("registered failed");
            Yii::$app->response->statusCode = 500; 
            return $this->asJson([
                'message' => 'Failed to register on blockchain server',
            ]);
        }
        $encrypted = Yii::$app->security->encryptByPassword($user_pkey->privateKey, $password);
        if($encrypted === null) {
            Yii::error("encrypted failed");
            Yii::$app->response->statusCode = 500; 
            return $this->asJson([
                'message' => 'Failed to encrypt key',
            ]);
        }

        $userKey = new UserKey();
        $userKey->user_id = Yii::$app->user->id;
        $userKey->secret_key = base64_encode($encrypted);

        if (!$userKey->save()) {
            Yii::$app->response->statusCode = 500; // Unprocessable Entity
            return $this->asJson([
                'message' => 'Failed to save private key',
                'errors' => $userKey->getErrors(),
            ]);
        }

        return $this->redirect(Url::to(['/mail/mail/index',['type' => 'secure']]));
    }

    public function actionEnrollAdmin()
    {
        $response = $this->fetchEnrollAdminOnBC();
        
        $admin_pkey = $response->privateKey;
        $encodedKey = base64_encode($admin_pkey);
        Yii::$app->response->cookies->add(new \yii\web\Cookie([
            'name' => 'adminPrivateKey',
            'value' => $encodedKey,
            'httpOnly' => true,
            'expire' => time() + 3600 * 24 * 7, 
        ]));

        return $encodedKey;
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

    private function fetchRegisterUserOnBC($key, $userId)
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
                        'userId' => $userId,
                        'affiliation' =>  "org1.department1",
                        'admin-pkey' => $key
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
}