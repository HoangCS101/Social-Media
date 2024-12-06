<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use humhub\widgets\SiteLogo;

$this->pageTitle = Yii::t('UserModule.auth', 'Password reset');
?>
<div class="container" style="text-align: center;">
    <?= SiteLogo::widget(['place' => 'login']); ?>
    <br>

    <div class="row">
        <div id="password-recovery-form" class="panel panel-default animated bounceIn"
             style="max-width: 300px; margin: 0 auto 20px; text-align: left;">
            <div class="panel-heading"><?= Yii::t('UserModule.auth', '<strong>Change</strong> your password'); ?></div>
            <div class="panel-body">

                <?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>

                <?= $form->field($model, 'newPassword')->passwordInput(['class' => 'form-control', 'id' => 'new_password', 'maxlength' => 255, 'value' => '']) ?>

                <?= $form->field($model, 'newPasswordConfirm')->passwordInput(['class' => 'form-control', 'maxlength' => 255, 'value' => '']) ?>

                <?= Html::submitButton(Yii::t('UserModule.auth', 'Change password'), ['class' => 'btn btn-primary', 'data-ui-loader' => '']); ?>

                <a class="btn btn-primary" data-ui-loader href="<?= Url::home() ?>">
                    <?= Yii::t('UserModule.auth', 'Back') ?>
                </a>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

    <div class="min-h-screen bg-inherit flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Change your password
            </h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">


            <?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>

            <?= $form->field($model, 'newPassword')->passwordInput(['class' => 'form-control', 'id' => 'new_password', 'maxlength' => 255, 'value' => '']) ?>

            <?= $form->field($model, 'newPasswordConfirm')->passwordInput(['class' => 'form-control', 'maxlength' => 255, 'value' => '']) ?>

            <?= Html::submitButton(Yii::t('UserModule.auth', 'Change password'), ['class' => 'btn btn-large btn-primary group relative w-full justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500', 'data-ui-loader' => '']); ?>

            <a class="btn btn-large btn-primary group relative w-full justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" data-ui-loader href="<?= Url::home() ?>">
                <?= Yii::t('UserModule.auth', 'Back') ?>
            </a>

            <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>

<script <?= \humhub\libs\Html::nonce() ?>>

    $(function () {
        // set cursor to email field
        $('#new_password').focus();
    });

    // Shake panel after wrong validation
    <?php if ($model->hasErrors()) { ?>
    $('#password-recovery-form').removeClass('bounceIn');
    $('#password-recovery-form').addClass('shake');
    $('#app-title').removeClass('fadeIn');
    <?php } ?>
</script>
