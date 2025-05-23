<?php
/**
 * @var $this View
 * @var FileSettingsForm $model
 * @var float $maxUploadSize
 * @var string $maxUploadSizeText
 * @var string $currentImageLibrary
 */

use humhub\modules\admin\models\forms\FileSettingsForm;
use humhub\modules\file\Module;
use humhub\modules\ui\form\widgets\ActiveForm;
use humhub\modules\ui\view\components\View;
use humhub\widgets\DataSaved;
use yii\helpers\Html;

/** @var Module $fileModule */
$fileModule = Yii::$app->getModule('file');

?>
<?php $this->beginContent('@admin/views/setting/_advancedLayout.php') ?>

<?php $form = ActiveForm::begin(['acknowledge' => true]); ?>

<?= $form->field($model, 'maxFileSize')->textInput(['class' => 'form-control', 'readonly' => $fileModule->settings->isFixed('maxFileSize')]); ?>
<p class="help-block" <?= ($model->maxFileSize > $maxUploadSize) ? 'style="color:' . $this->theme->variable('danger') . ' !important"' : '' ?>>
    <?= Yii::t('AdminModule.settings', 'PHP reported a maximum of {maxUploadSize} MB', ['{maxUploadSize}' => $maxUploadSizeText]); ?>
</p>

<?= $form->field($model, 'allowedExtensions')->textarea(['class' => 'form-control']); ?>
<p class="help-block"><?= Yii::t('AdminModule.settings', 'Comma separated list. Leave empty to allow all.'); ?></p>

<br/>

<?= $form->field($model, 'useXSendfile')->checkbox(['disabled' => $fileModule->settings->isFixed('useXSendfile')]); ?>
<?= $form->field($model, 'excludeMediaFilesPreview')->checkbox(['disabled' => $fileModule->settings->isFixed('excludeMediaFilesPreview')]); ?>

<hr>

<?= Html::submitButton(Yii::t('AdminModule.settings', 'Save'), ['class' => 'btn btn-primary', 'data-ui-loader' => ""]); ?>

<?= DataSaved::widget(); ?>
<?php ActiveForm::end(); ?>

<?php $this->endContent(); ?>
