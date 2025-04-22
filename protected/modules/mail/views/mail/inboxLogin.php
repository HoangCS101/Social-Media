<?php

use yii\widgets\ActiveForm;
use humhub\libs\Html;
use humhub\widgets\ModalButton;
use humhub\widgets\ModalDialog;
use humhub\modules\mail\helpers\Url;
?>
<div id='secure-login-form'>
    <?php $form = ActiveForm::begin(['enableClientValidation' => false, 'action' => Url::toLoginSecureChat()]) ?>
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
            <div class='col-md-3'>
                <?= Html::submitButton(Yii::t('MailModule.views_mail_create', 'Login'), ['data-ui-loader' => "",'class' => 'btn btn-primary w-100']) ?>
            </div>

        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$js = <<<JS
$(document).ready(function () {
    
    $('#toggle-password').on('change', function() {
        const input = $('#secure-password');
        input.attr('type', this.checked ? 'text' : 'password');
    });

    $(document).on('submit', '#secure-login-form form', function (e) {
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
                console.log([response]);
                showError(response.responseJSON.message);
            },
            complete: function () {
                // HumHub.ui.loader.reset(); // 👈 Tắt loading của data-ui-loader
                button.prop('disabled', false);
            }
                
        });
    });
    function showError(message) {
        $('#secure-login-form .form-group.field-secure-password').addClass('has-error');
        $('#secure-login-form .form-group.field-secure-password .help-block').text(message);
        $('#secure-password').val('');
        
    }
})
JS;

$this->registerJs($js, \yii\web\View::POS_READY);
?>

