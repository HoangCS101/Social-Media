<?php

use humhub\libs\Html;
use humhub\modules\user\models\Password;
use humhub\widgets\Button;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use humhub\widgets\SiteLogo;

/* @var $model Password */

$this->pageTitle = Yii::t('UserModule.auth', 'Change password');
?>
<div class="container" style="text-align: center;">
    <!-- <?= SiteLogo::widget(['place' => 'login']); ?>
    <br>

    <div class="row">
        <div id="must-change-password-form" class="panel panel-default animated bounceIn"
             style="max-width: 300px; margin: 0 auto 20px; text-align: left;">
            <div class="panel-heading"><?= Yii::t('UserModule.auth', '<strong>Change</strong> Password'); ?></div>
            <div class="panel-body">

                <?php $form = ActiveForm::begin(); ?>

                <p><?= Yii::t('UserModule.auth', 'Due to security reasons you are required to change your password in order to access the platform.'); ?></p>


                <?php if ($model->isAttributeSafe('currentPassword')): ?>
                    <?= $form->field($model, 'currentPassword')->passwordInput(['maxlength' => 45]); ?>
                    <hr>
                <?php endif; ?>

                <?= $form->field($model, 'newPassword')->passwordInput(['maxlength' => 45]); ?>
                <?= $form->field($model, 'newPasswordConfirm')->passwordInput(['maxlength' => 45]); ?>

                <hr>
                <?= Button::primary(Yii::t('UserModule.auth', 'Confirm'))->submit()->left() ?>

                <?php ActiveForm::end(); ?>

                <?= Button::defaultType(Yii::t('UserModule.auth', 'Log out'))->link(Url::toRoute('/user/auth/logout'), false)->options(['data-method' => 'POST'])->right() ?>

            </div>
        </div>
    </div> -->

    <div class="min-h-screen bg-inherit flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <!-- <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Sign in to your account
            </h2>
            <p class="mt-2 text-center text-sm text-red-600">
                Or
                <a href="#" class="font-medium text-red-600 hover:text-blue-500">
                    create an account
                </a>
            </p>
        </div> -->

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <?php $form = ActiveForm::begin(); ?>

                <p><?= Yii::t('UserModule.auth', 'Due to security reasons you are required to change your password in order to access the platform.'); ?></p>


                <?php if ($model->isAttributeSafe('currentPassword')): ?>
                    <?= $form->field($model, 'currentPassword')->passwordInput(['maxlength' => 45]); ?>
                    <hr>
                <?php endif; ?>

                <?= $form->field($model, 'newPassword')->passwordInput(['maxlength' => 45]); ?>
                <?= $form->field($model, 'newPasswordConfirm')->passwordInput(['maxlength' => 45]); ?>

                <hr>
                <?= Button::primary(Yii::t('UserModule.auth', 'Confirm'))->submit()->left() ?>

                <?php ActiveForm::end(); ?>

                <?= Button::defaultType(Yii::t('UserModule.auth', 'Log out'))->link(Url::toRoute('/user/auth/logout'), false)->options(['data-method' => 'POST'])->right() ?>

            </div>
        </div>
    </div>


</div>




<script <?= Html::nonce() ?>>
    $(function () {
        // set cursor to current password field
        $('#password-currentpassword').focus();
    });
</script>
