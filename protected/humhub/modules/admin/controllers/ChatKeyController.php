<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\admin\controllers;
use humhub\modules\user\models\UserKey;
use humhub\modules\admin\components\Controller;

use yii\httpclient\Client;

/**
 * User management
 *
 * @since 0.5
 */
class ChatKeyController extends Controller
{
    public function registerIdentity($userId)
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

    public function identity($userId)
    {
        if($key = UserKey::findOne(['user_id' => $userId])) {
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl('http://nodejs-server:3000/identity')
                ->setData(['id' => $userId, 'secret_key' => $key->secret_key])
                ->send();

            if ($response->isOk) {
                $data = $response->data;
                
                // Lưu key vào database
                if (isset($data['secret_key'])) {
                    UserKey::saveKey($userId, $data['secret_key']);
                }

                return $data;
            }
        }
        
        return ['success' => false, 'error' => 'Registration failed'];
    }
}