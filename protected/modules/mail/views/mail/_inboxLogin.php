<?php

use yii\widgets\ActiveForm;
use humhub\libs\Html;
use humhub\widgets\ModalButton;
use humhub\modules\mail\helpers\Url;
?>
<?php $form = ActiveForm::begin(['enableClientValidation' => false]) ?>
<div class="modal-body">
    <div class="row">

        <!-- Password Input -->
        <div class="col-md-9 align-items-center">
            <?= $form->field($model, 'password', [
                'template' => "{input}\n{error}", 
            ])->passwordInput([
                'class' => 'form-control',
                'id' => 'secure-password',
                'placeholder' => Yii::t('MailModule.base', 'Password for secure chat'),

            ])->label(false) ?>
        </div>

        <!-- Submit Button -->
        <?= ModalButton::submitModal(Url::toLoginSecureChat(), Yii::t('MailModule.views_mail_create', 'Send')) ?>

    </div>
</div>

<?php ActiveForm::end(); ?>

<?php
// JavaScript toggle password
$this->registerJs(<<<JS
    $('#toggle-password').on('change', function() {
        const input = $('#secure-password');
        input.attr('type', this.checked ? 'text' : 'password');
    });
JS);
?>
