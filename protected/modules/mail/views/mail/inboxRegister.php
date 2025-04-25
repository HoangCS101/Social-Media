<?php

use yii\widgets\ActiveForm;
use humhub\libs\Html;
use humhub\widgets\ModalButton;
use humhub\modules\mail\helpers\Url;
?>
<div id="secure-register-form">
    <?php $form = ActiveForm::begin(['enableClientValidation' => false, 'action' => Url::toRegisterSecureChat()]) ?>
    <div class="modal-body">
        <div class="pb-4 flex flex-col align-items-center gap-4">
            <?php if ($requestStatus === 'not_sent'): ?>
                <?= Yii::t('MailModule.views_mail_create', 'You have not sent request, please send request to admin' ) ?>
            <?php elseif ($requestStatus === 'rejected'): ?>                
                <?= Yii::t('MailModule.views_mail_create', 'Your request has been rejected, please contact admin' ) ?>
            <?php endif; ?>
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
                <?= Html::submitButton(Yii::t('MailModule.views_mail_create', 'Register'), ['class' => 'btn btn-primary w-100']) ?>
            </div>

        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
// JavaScript toggle password
$this->registerJs(<<<JS
    $('#toggle-password').on('change', function() {
        const input = $('#secure-password');
        input.attr('type', this.checked ? 'text' : 'password');
    });

    (document).on('submit', '#secure-register-form form', function (e) {
        e.preventDefault();
        const form = $(this);
        const button = form.find('button[type=submit]');


        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            success: function (response) {
            },
            error: function (response) {
                showError(response.responseJSON.message);
            },
            complete: function () {
            }
                
        });
    });
    function showError(message) {
        $('#secure-register-form .form-group.field-secure-password').addClass('has-error');
        $('#secure-register-form .form-group.field-secure-password .help-block').text(message);
        $('#secure-password').val('');
        
    }
JS);
?>
