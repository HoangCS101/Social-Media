<?php

use humhub\libs\Html;
use humhub\modules\user\models\forms\Login;
use humhub\modules\user\models\Invite;
use humhub\modules\user\widgets\AuthChoice;
use humhub\widgets\SiteLogo;
use yii\captcha\Captcha;
use yii\widgets\ActiveForm;
use yii\helpers\Url;


$this->pageTitle = Yii::t('UserModule.auth', 'Login');

/* @var $canRegister bool */
/* @var $model Login */
/* @var $invite Invite */
/* @var $info string */
/* @var $passwordRecoveryRoute string|array|null */
/* @var $showLoginForm bool */
/* @var $showRegistrationForm bool */

?>

<div id="user-auth-login-modal" class="container" style="text-align: center;">

    <!-- <div class="panel panel-default animated bounceIn" id="login-form"
         style="max-width: 300px; margin: 0 auto 20px; text-align: left;">

        <div class="panel-heading"><?= Yii::t('UserModule.auth', 'SIGN IN'); ?></div>

        <div class="panel-body">

            <?php if (Yii::$app->session->hasFlash('error')): ?>
                <div class="alert alert-danger" role="alert">
                    <?= Yii::$app->session->getFlash('error') ?>
                </div>
            <?php endif; ?>

            <?php if (AuthChoice::hasClients()): ?>
                <?= AuthChoice::widget(['showOrDivider' => $showLoginForm]) ?>
            <?php else: ?>
                <?php if ($canRegister) : ?>
                    <p><?= Yii::t('UserModule.auth', "If you're already a member, please login with your username/email and password."); ?></p>
                <?php elseif ($showLoginForm): ?>
                    <p><?= Yii::t('UserModule.auth', "Please login with your username/email and password."); ?></p>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($showLoginForm): ?>
                <?php $form = ActiveForm::begin(['id' => 'account-login-form', 'enableClientValidation' => false]); ?>
                <?= $form->field($model, 'username')->textInput(['id' => 'login_username', 'placeholder' => $model->getAttributeLabel('username'), 'aria-label' => $model->getAttributeLabel('username')])->label(false); ?>
                <?= $form->field($model, 'password')
                    ->passwordInput(['id' => 'login_password', 'placeholder' => $model->getAttributeLabel('password'), 'aria-label' => $model->getAttributeLabel('password')])
                    ->label(false); ?>
                <?= $form->field($model, 'rememberMe')->checkbox(); ?>

                <hr>
                <div class="row">
                    <div class="col-md-4">
                    </div>
                    <?php if ($passwordRecoveryRoute) : ?>
                        <div class="col-md-8 text-right">
                            <small>
                                <?= Html::a(
                                    Html::tag('br') . Yii::t('UserModule.auth', 'Forgot your password?'),
                                    $passwordRecoveryRoute,
                                    [
                                        'id' => 'password-recovery-link',
                                        'target' => is_array($passwordRecoveryRoute) ? '_self' : '_blank',
                                        'data' => [
                                            'pjax-prevent' => true,
                                        ]
                                    ]
                                ) ?>
                            </small>
                        </div>
                    <?php endif; ?>
                </div>
                <?php ActiveForm::end(); ?>
            <?php endif; ?>
        </div>
    </div> -->

    <div class="min-h-screen bg-inherit flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Sign in to your account
            </h2>
            <p class="mt-2 text-center text-sm text-red-600">
                Or
                <a class="font-medium text-red-600 hover:text-blue-500"  data-action-url="<?= Url::to(['/user/password-recovery']) ?>" data-ui-loader>
                    create an account
                </a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                    <?php $form = ActiveForm::begin(['id' => 'account-login-form', 'enableClientValidation' => false]); ?>

                    <div>
                        <label for="email" class="block text-left text-sm font-medium text-gray-700">
                            Email address
                        </label>
                        <div class="mt-1">
                            <?= $form->field($model, 'username')->textInput(['id' => 'login_username', 'placeholder' => $model->getAttributeLabel('username'), 'aria-label' => $model->getAttributeLabel('username')])->label(false); ?>     
                        </div>
                    </div>          

                    <div>
                        <label for="password" class="block text-left text-sm font-medium text-gray-700">
                            Password
                        </label>
                        <div class="mt-1">
                            <?= $form->field($model, 'password')
                            ->passwordInput(['id' => 'login_password', 'placeholder' => $model->getAttributeLabel('password'), 'aria-label' => $model->getAttributeLabel('password')])
                            ->label(false); ?>
                        </div>
                    </div>

                    <div class="flex items-top justify-between">
                        <?= $form->field($model, 'rememberMe')->checkbox(); ?>
                        <div class="form-group">
                            <?= Html::a(
                                Yii::t('UserModule.auth', 'Forgot your password?'),
                                $passwordRecoveryRoute,
                                [
                                    'id' => 'password-recovery-link',
                                    'class' => 'font-medium text-blue-600 hover:text-blue-500',
                                    'target' => is_array($passwordRecoveryRoute) ? '_self' : '_blank',
                                    'data' => [
                                        'pjax-prevent' => true,
                                    ]
                                ]
                            ) ?>
                        </div>
                    </div>

                    <div>
                       <?= Html::submitButton(Yii::t('UserModule.auth', 'Sign in'), ['id' => 'login-button', 'data-ui-loader' => "", 'class' => 'btn btn-large btn-primary relative w-full justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']); ?>
                    </div>
                    <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>


    <?= humhub\widgets\LanguageChooser::widget(); ?>
</div>


<script <?= Html::nonce() ?>>
    $(function () {
        // set cursor to login field
        $('#login_username').focus();
    });

    // Shake panel after wrong validation
    <?php if ($model->hasErrors()) { ?>
    $('#login-form').removeClass('bounceIn');
    $('#login-form').addClass('shake');
    $('#register-form').removeClass('bounceInLeft');
    $('#app-title').removeClass('fadeIn');
    <?php } ?>

    // Shake panel after wrong validation
    <?php if ($invite->hasErrors()) { ?>
    $('#register-form').removeClass('bounceInLeft');
    $('#register-form').addClass('shake');
    $('#login-form').removeClass('bounceIn');
    $('#app-title').removeClass('fadeIn');
    <?php } ?>

    <?php if ($invite->showCaptureInRegisterForm()) { ?>
    $('#register-email').on('focus', function () {
        $('#registration-form-captcha').fadeIn(500);
    });
    <?php } ?>

</script>
