<?php
/**
 * Created by PhpStorm.
 * User: kingb
 * Date: 29.07.2018
 * Time: 09:29
 */

namespace humhub\modules\mail\widgets;

use humhub\widgets\JsWidget;
use humhub\modules\mail\helpers\Url;

class ConversationView extends JsWidget
{
    /**
     * @inheritdoc
     */
    public $jsWidget = 'mail.ConversationView';
 
    /**
     * @inheritdoc
     */
    public $id = 'mail-conversation-root';


    /**
     * @inheritdoc
     */
    public $init = true;

    /**
     * @var int
     */
    public $messageId;
    public $messageType;
    public $isLoggedFabric = false;


    public function getData()
    {
        return [
            'message-type' => $this->messageType,
            'message-id' => $this->messageId,
            'is-logged-fabric' => $this->isLoggedFabric,
            'load-message-url' => Url::toLoadMessage($this->messageType),
            'load-update-url' => Url::toUpdateMessage(),
            'load-more-url' => Url::toLoadMoreMessages(),
            'mark-seen-url' => Url::toNotificationSeen(),
            'handle-create-url' => Url::toHandleSave('create'),
            'handle-update-url' => Url::toHandleSave('update'),
            'handle-delete-url' => Url::toHandleSave('delete'),
        ];
    }
}
