<?php

use humhub\modules\admin\models\forms\ApproveUserForm;
use humhub\modules\content\widgets\richtext\RichTextField;
use humhub\modules\ui\form\widgets\ActiveForm;
use humhub\modules\user\models\User;
use humhub\widgets\Button;
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $model User */
/* @var $approveFormModel ApproveUserForm */
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <strong><?= Yii::t('MailModule.base', 'Approve Register Secure Chat Request') ?></strong>
    </div>

    <div class="panel-body">
        <h2 style="font-size: 16px;">
            <?= Yii::t('MailModule.base', 'Request from <strong>{displayName}</strong>', [
                'displayName' => Html::encode($user->username)
            ]); ?>
        </h2>
        <?php if ($model->status == 'accepted'): ?>
            <div class="alert alert-success mt-2">
                <strong><?= Yii::t('MailModule.base', 'Request Approved'); ?></strong>
            </div>
            <?= Button::defaultType(Yii::t('MailModule.base', 'Back'))->link(Url::to(['/'])) ?>
        <?php elseif ($model->status == 'rejected'): ?>
            <div class="alert alert-danger mt-2">
                <strong><?= Yii::t('MailModule.base', 'Request Rejected'); ?></strong>
            </div>
            <?= Button::defaultType(Yii::t('MailModule.base', 'Back'))->link(Url::to(['/'])) ?>

        <?php else : ?>
            <div class="pull-right mt-5">
                <?= Html::beginForm(Url::to(['/admin/secure/accept', 'id' => $model->id]), 'post') ?>
                    <?= Html::submitButton(Yii::t('MailModule.base', 'Approve Request'), ['class' => 'btn btn-success']) ?>
                    <?= Button::danger(Yii::t('MailModule.base', 'Reject'))->link(Url::to(['/admin/secure/decline', 'id' => $model->id])); ?>
                    <?= Button::defaultType(Yii::t('MailModule.base', 'Back'))->link(Url::to(['/'])); ?>
                <?= Html::endForm() ?>
            </div>
        <?php endif; ?>

        


    </div>
</div>
