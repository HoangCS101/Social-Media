<?php

use humhub\modules\mail\assets\MailMessengerAsset;
use humhub\modules\mail\models\UserMessage;
use humhub\modules\mail\widgets\ConversationView;
use humhub\modules\ui\view\helpers\ThemeHelper;

/* @var $messageId int */
/* @var $userMessages UserMessage[] */

MailMessengerAsset::register($this);
?>
<div class="<?= ThemeHelper::isFluid() ? 'container-fluid' : 'container' ?><?= $messageId ? ' mail-conversation-single-message' : '' ?>">
    <div class="row" style="margin-top: -30px">
        <div class="col-md-4 p-0" style="border-radius: none;">
            <?= $this->render('_conversation_sidebar') ?>
        </div>

        <div class="col-md-8 messages p-0" style="border-radius: none;">
            <?= ConversationView::widget(['messageId' => $messageId]) ?>
        </div>
    </div>
</div>
