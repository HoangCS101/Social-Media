<?php

use humhub\modules\mail\models\forms\InboxFilterForm;
use humhub\modules\mail\permissions\StartConversation;
use humhub\modules\mail\widgets\NewMessageButton;
use humhub\modules\mail\widgets\InboxFilter;
use humhub\modules\mail\widgets\SecureChatMenu;
use humhub\modules\ui\icon\widgets\Icon;


$canStartConversation = Yii::$app->user->can(StartConversation::class);

$filterModel = new InboxFilterForm();
// $type = Yii::$app->request->get('type', 'normal');
?>
<div id="mail-conversation-overview" class="panel panel-default mail-inbox-messages rounded-none">
    <div class="panel-heading rounded-none">
        <a data-action-click="mail.inbox.toggleInbox" style="font-size:25px">
            <span class="visible-xs-inline"><?=Icon::get('bars')?></span> <?= Yii::t('MailModule.views_mail_index', 'Conversations') ?>
        </a>
        <?php if ($canStartConversation) : ?>
            <?= NewMessageButton::widget(['icon' => 'plus', 'label' => '', 'id' => 'mail-conversation-create-button'])?>
        <?php endif; ?>

        <div class="inbox-wrapper">
         <?= InboxFilter::widget(['model' => $filterModel]) ?>
        </div>

    </div>
    <div class="w-full bg-white shadow-md rounded-lg h-[80vh]">

        <?= SecureChatMenu::widget([
            'filter' => $filterModel,
            'options' => ['class' => '[&>ul]:pb-0 [&>li]:w-1/2']
        ]); ?>

        <div id="inbox-list-container">
            <?= $this->render('_inboxList', ['type' => $type, 'filter' => $filterModel]); ?>
        </div>
    </div>
    
</div>
