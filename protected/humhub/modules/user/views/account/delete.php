<?php

use humhub\widgets\Button;
use yii\bootstrap\ActiveForm;

?>
<?php $this->beginContent('@user/views/account/_userProfileLayout.php') ?>

<strong><?= Yii::t('UserModule.account', 'Are you sure that you want to delete your account?'); ?></strong>
<br/>
<br/>
<div
    class="alert alert-danger"><?= Yii::t('UserModule.account', 'All your personal data will be irrevocably deleted.'); ?></div>

<?php $form = ActiveForm::begin(); ?>

<?php if ($model->isAttributeRequired('currentPassword')): ?>
    <?= $form->field($model, 'currentPassword')->passwordInput(['maxlength' => 45, 'placeholder' => Yii::t('UserModule.account', 'Enter your password to continue')]); ?>
<?php else: ?>
    <?= $form->field($model, 'currentPassword')->hiddenInput()->label(false); ?>
<?php endif; ?>

<br/>

<?= Button::danger(Yii::t('UserModule.account', 'Delete account'))
    ->confirm(Yii::t('UserModule.account', 'Are you sure that you want to delete your account?'), Yii::t('UserModule.account', 'All your personal data will be irrevocably deleted.'))
    ->submit() ?>

<?php ActiveForm::end(); ?>

<?php $this->endContent(); ?>


