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
            ['permissions' => [ManageUsers::class, ManageGroups::class]],
            ['permissions' => [ManageSettings::class], 'actions' => ['index']],
        ];
    }

    public function actionRegisterUser($userId)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('http://nodejs-server:3000/register')
            ->setData(['userId' => $userId])
            ->send();

        if ($response->isOk) {
            $data = $response->data;
            
            // Lưu key vào database
            if (isset($data['secret_key'])) {
                UserKey::saveKey($userId, $data['secret_key']);
            }

            return $data;
        }
        return ['success' => false, 'error' => 'Registration failed'];
    }

    public function actionEnrollAdmin()
    {
        $response = $this->fetchEnrollAdminOnBC();
        $admin_pkey = $response->privateKey;
        $encodedKey = base64_encode($admin_pkey);
        file_put_contents(Yii::getAlias('@app/secure/admin.key'), $encodedKey);
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
}