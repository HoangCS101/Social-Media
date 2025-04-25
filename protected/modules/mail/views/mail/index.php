<?php

use humhub\modules\mail\assets\MailMessengerAsset;
use humhub\modules\mail\models\UserMessage;
use humhub\modules\mail\widgets\ConversationView;
use humhub\modules\ui\view\helpers\ThemeHelper;


/* @var $messageId int */
/* @var $type string */
/* @var $userMessages UserMessage[] */

MailMessengerAsset::register($this);
$isLoggedFabric = Yii::$app->request->cookies->getValue('isLoggedFabric', false);
?>
<div class="<?= ThemeHelper::isFluid() ? 'container-fluid' : 'container' ?><?= $messageId ? ' mail-conversation-single-message' : '' ?>">
    <div class="row h-inherit" style="margin-top: -30px ">
        <div class="col-md-4 p-0 h-[610px] bg-white" style="border-radius: none;">
            <?= $this->render('_conversation_sidebar', ['type' => $messageType, 'isRegisteredFabric' => $isRegisteredFabric, 'isLoggedFabric' => $isLoggedFabric, 'model' => $model]) ?>
        </div>

        <div class="col-md-8 h-inherit messages p-0" style="border-radius: none;">
            <?= ConversationView::widget(['messageId' => $messageId, 'messageType' => $messageType, 'isLoggedFabric' => $isLoggedFabric]) ?>
        </div>
    </div>
</div>
