<?php

use humhub\modules\installer\controllers\ConfigController;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>
<div id="name-form" class="panel panel-default animated fadeIn">

    <div class="panel-heading">
        <?php echo Yii::t('InstallerModule.base', '<strong>Configuration</strong>'); ?>
    </div>

    <div class="panel-body">

        <p><?php echo Yii::t('InstallerModule.base', 'To simplify the configuration, we have predefined setups for the most common use cases with different options for modules and settings. You can adjust them during the next step.'); ?></p>
        <br>

        <?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>

        <?=
        $form->field($model, 'useCase')->radioList([
            ConfigController::USECASE_SOCIAL_INTRANET => Yii::t('InstallerModule.base', 'My company (Social Intranet / Project management)'),
            ConfigController::USECASE_EDUCATION => Yii::t('InstallerModule.base', 'My educational institution (school, university)'),
            ConfigController::USECASE_CLUB => Yii::t('InstallerModule.base', 'My club'),
            ConfigController::USECASE_COMMUNITY => Yii::t('InstallerModule.base', 'My community'),
            ConfigController::USECASE_OTHER => Yii::t('InstallerModule.base', 'Skip this step, I want to set up everything manually'),
        ]);
        ?>

        <hr>

        <?php echo Html::submitButton(Yii::t('base', 'Next'), ['class' => 'btn btn-primary', 'data-ui-loader' => '']); ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
