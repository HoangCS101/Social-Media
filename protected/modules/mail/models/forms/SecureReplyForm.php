<?php

namespace humhub\modules\mail\models\forms;

use humhub\modules\mail\helpers\Url;
use humhub\modules\mail\models\Message;
use humhub\modules\mail\models\SecureMessageEntry;
use yii\httpclient\Client;
use Yii;
use yii\base\Model;

/**
 * @package humhub.modules.mail.forms
 * @since 0.5
 */
class SecureReplyForm extends Model
{
    /**
     * @var Message
     */
    public $model;

    /**
     * @var string
     */
    public $message;

    /**
     * @var SecureMessageEntry
     */
    public $reply;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['message', 'required'],
            ['message', 'validateRecipients'],
        ];
    }

    public function validateRecipients($attribute)
    {
        if ($this->model->isBlocked()) {
            $this->addError($attribute, Yii::t('MailModule.base', 'You are not allowed to reply to users {userNames}!', [
                'userNames' => implode(', ', $this->model->getBlockerNames()),
            ]));
        }
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return [
            'message' => Yii::t('MailModule.forms_ReplyMessageForm', 'Message'),
        ];
    }

    public function getUrl()
    {
        return Url::toReply($this->model, 'secure');
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        

        $this->reply = new SecureMessageEntry([
            'message_id' => $this->model->id,
            'user_id' => Yii::$app->user->id,
            'content' => $this->message
        ]);

        if ($this->reply->save()) {
            $this->reply->refresh(); // Update created_by date, otherwise db expression is set...

            // try {
            //     $client = new Client();
            //     $response = $client->createRequest()
            //         ->setMethod('POST')
            //         ->setUrl('http://localhost:3000/api/messages')
            //         ->addHeaders(['content-type' => 'application/json'])
            //         ->setContent(json_encode([
            //             'messageId' => $this->reply->id,
            //             'chatboxId' => $this->model->id,
            //             'userId' => Yii::$app->user->id,
            //             'content' => $this->reply->content,
            //             'type' => $this->reply->type,
            //             'createdAt' => $this->reply->createdAt
            //         ]))
            //         ->send();
        
            //     if (!$response->isOk) {
                    
            //         Yii::error("Failed to fetch secure message from Fabric: " . $response->content, __METHOD__);
            //         return false;
            //     }
                
        
            // } catch (\Exception $e) {
            //     Yii::error("Error when calling Node.js API: " . $e->getMessage(), __METHOD__);
            // }
            // $this->reply->notify();
            $this->reply->fileManager->attach(Yii::$app->request->post('fileList'));
            return true;
        }
    
        return false;
    }
}
