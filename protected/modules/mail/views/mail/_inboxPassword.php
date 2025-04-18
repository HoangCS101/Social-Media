<?php

use humhub\libs\Html;
use humhub\modules\mail\helpers\Url;
use humhub\widgets\ModalButton;
use humhub\widgets\ModalDialog;
?>

<?php Html::beginForm(Url::toLoginSecureChat(), 'POST', ['data-ui-loader' => '']) ?>

<div class="modal-body">
    <div class="row align-items-center">
        <!-- Label -->

        <!-- Input -->
        <div class="col-md-9">
            <?= Html::textInput('password', '', [
                'class' => 'form-control',
                'placeholder' => Yii::t('MailModule.base', 'Password for secure chat')
            ]) ?>
        </div>

        <!-- Submit Button -->
        <div class="col-md-3">
            <?= ModalButton::submitModal('', Yii::t('MailModule.views_mail_create', 'Send')) ?>
        </div>
    </div>
</div>

<?php Html::endForm() ?>
