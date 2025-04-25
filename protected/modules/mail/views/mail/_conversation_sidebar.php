<?php

use humhub\modules\mail\models\forms\InboxFilterForm;
use humhub\modules\mail\permissions\StartConversation;
use humhub\modules\mail\widgets\NewMessageButton;
use humhub\modules\mail\widgets\InboxFilter;
use humhub\modules\mail\widgets\SecureChatMenu;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\modules\mail\helpers\Url;
use humhub\widgets\ModalButton;



$canStartConversation = Yii::$app->user->can(StartConversation::class);


// $type = Yii::$app->request->get('type', 'normal');
$filterModel = new InboxFilterForm();
?>
<div id="mail-conversation-overview h-inherit" class="panel panel-default mail-inbox-messages rounded-none">
    <div class="panel-heading rounded-none h-[84px]">
        <a data-action-click="mail.inbox.toggleInbox" style="font-size:25px">
            <span class="visible-xs-inline"><?=Icon::get('bars')?></span> <?= Yii::t('MailModule.views_mail_index', 'Conversations') ?>
        </a>
        <?php if ($canStartConversation) : ?>
            <?= NewMessageButton::widget(['icon' => 'plus', 'label' => '', 'id' => 'mail-conversation-create-button'])?>
        <?php endif; ?>

        <div class="inbox-wrapper">
        </div>
 
    </div>
    <div class="w-full bg-white shadow-md rounded-lg h-[80vh]">

        <?= SecureChatMenu::widget([
            'filter' => $filterModel,
        ]); ?>
        <?php if ($type === 'secure' && $requestStatus === 'pending'): ?>
            <div class="pb-4 pt-4 flex flex-col align-items-center gap-4">
                <?= Yii::t('MailModule.views_mail_create', 'You have sent request, please wait until admin approve' ) ?>
            </div>
        <?php elseif ($type === 'secure' && ($requestStatus === 'not_sent' || $requestStatus === 'rejected')): ?>
            <?= $this->render('inboxRegister' ,['model' => $model, 'requestStatus' => $requestStatus]); ?>
        <?php elseif ($type === 'secure' && !$isLoggedFabric): ?>
            <?= $this->render('inboxLogin' ,['model' => $model]); ?>
        <?php else : ?>
            <div id="inbox-list-container">
                <?= $this->render('_inboxList', ['type' => $type, 'filter' => $filterModel]); ?>
            </div>
        <?php endif ?>
    </div>
    
</div>
