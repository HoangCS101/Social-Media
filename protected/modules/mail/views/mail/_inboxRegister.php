<?php

use yii\widgets\ActiveForm;
use humhub\libs\Html;
use humhub\widgets\ModalButton;
use humhub\modules\mail\helpers\Url;
?>
<?php $form = ActiveForm::begin(['enableClientValidation' => false]) ?>
<div class="modal-body">
    <div class="pb-4 flex flex-col align-items-center gap-4">
        <?= Yii::t('MailModule.views_mail_create', 'You haven\'t register secure messaging, please register in advance.' ) ?>
    </div>
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
         <div class="col-md-3">
            <?= ModalButton::submitModal(Url::toRegisterSecureChat(), Yii::t('MailModule.views_mail_create', 'Register'),
            ['class' => 'h-[35px]'])?>
         </div>

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
