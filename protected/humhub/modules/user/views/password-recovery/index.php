<?php

use humhub\modules\user\models\forms\AccountRecoverPassword;
use humhub\widgets\Button;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use humhub\widgets\SiteLogo;
use yii\captcha\Captcha;

$this->pageTitle = Yii::t('UserModule.auth', 'Password recovery');

/**
 * @var $model AccountRecoverPassword
 */

?>
<div class="container" style="text-align: center;">
    <!-- <?= SiteLogo::widget(['place' => 'login']); ?>
    <br>

    <div class="row">
        <div id="password-recovery-form" class="panel panel-default animated bounceIn"
             style="max-width: 300px; margin: 0 auto 20px; text-align: left;">
            <div class="panel-heading"><?= Yii::t('UserModule.auth', '<strong>Password</strong> recovery'); ?></div>
            <div class="panel-body">

                <?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>

                <p><?= Yii::t('UserModule.auth', 'Just enter your e-mail address. We\'ll send you recovery instructions!'); ?></p>

                <?= $form->field($model, 'email')->textInput(['class' => 'form-control', 'id' => 'email_txt', 'placeholder' => Yii::t('UserModule.auth', 'Your email')])->label(false) ?>

                <div class="form-group">
                    <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                        'model' => $model,
                        'attribute' => 'verifyCode',
                        'captchaAction' => '/user/auth/captcha',
                        'options' => ['class' => 'form-control', 'placeholder' => Yii::t('UserModule.auth', 'Enter security code above')]
                    ])->label(false);
                    ?>
                </div>

                <hr>
                <?= Html::submitButton(Yii::t('UserModule.auth', 'Reset password'), ['class' => 'btn btn-primary', 'data-ui-loader' => ""]); ?>
                <?= Button::primary(Yii::t('UserModule.auth', 'Back'))->link(Url::home())->pjax(false) ?>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div> -->


    <div class="min-h-screen bg-inherit flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Password Recovery
            </h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">

                <?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>

                <p class="text-left"><?= Yii::t('UserModule.auth', 'Just enter your e-mail address. We\'ll send you recovery instructions!'); ?></p>

                <?= $form->field($model, 'email')->textInput(['class' => 'form-control', 'id' => 'email_txt', 'placeholder' => Yii::t('UserModule.auth', 'Your email')])->label(false) ?>

                <div class="form-group">
                    <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                        'model' => $model,
                        'attribute' => 'verifyCode',
                        'captchaAction' => '/user/auth/captcha',
                        'options' => ['class' => 'form-control', 'placeholder' => Yii::t('UserModule.auth', 'Enter security code above')]
                    ])->label(false);
                    ?>
                </div>

                <hr>
                <div class="row">
                    <div class="col-6">
                        <?= Html::submitButton(Yii::t('UserModule.auth', 'Reset password'), ['class' => 'btn btn-large btn-primary group relative w-full justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500', 'data-ui-loader' => ""]); ?>
                    </div>
                    <div class="col-6">
                        <?= Button::primary(Yii::t('UserModule.auth', 'Back'))
                        ->link(Url::home())
                        ->pjax(false)
                        ->cssClass('btn btn-large btn-primary group relative w-full justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500') ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>

<script <?= \humhub\libs\Html::nonce() ?>>

    $(function () {
        // set cursor to email field
        $('#email_txt').focus();
    });

    // Shake panel after wrong validation
    <?php if ($model->hasErrors()) : ?>
    $('#password-recovery-form').removeClass('bounceIn');
    $('#password-recovery-form').addClass('shake');
    $('#app-title').removeClass('fadeIn');
    <?php endif; ?>
</script>
