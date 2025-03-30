<?php

namespace humhub\modules\mail\models\forms;

use humhub\modules\mail\helpers\Url;
use humhub\modules\mail\models\Message;
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
     * @var MessageEntry
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
        return Url::toReply($this->model);
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $nodeServerUrl = 'http://node-server/api/getSecureMessage';
        $client = new \yii\httpclient\Client();

        try {
            $response = $client->post($nodeServerUrl, [
                'message_id' => $this->model->id,
                'user_id' => Yii::$app->user->id,
            ])->send();
    
            if (!$response->isOk) {
                Yii::error("Failed to fetch secure message from Fabric: " . $response->content, __METHOD__);
                return false;
            }
            
    
        } catch (\Exception $e) {
            Yii::error("Error when calling Node.js API: " . $e->getMessage(), __METHOD__);
        }
    
        return false;
    }
}
