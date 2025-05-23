<?php

use humhub\libs\Html;
use humhub\modules\admin\models\forms\SpaceSettingsForm;
use humhub\widgets\Button;
use humhub\modules\ui\form\widgets\ActiveForm;

/* @var $model SpaceSettingsForm */
/* @var $joinPolicyOptions array */
/* @var $visibilityOptions array */
/* @var $contentVisibilityOptions array */
/* @var $indexModuleSelection array */

?>
<h4><?= Yii::t('AdminModule.space', 'Space Settings'); ?></h4>
<div class="help-block">
    <?= Yii::t('AdminModule.space', 'Here you can define your default settings for new spaces. These settings can be overwritten for each individual space.'); ?>
</div>

<?php $form = ActiveForm::begin(['id' => 'space-settings-form', 'acknowledge' => true]); ?>

<?= humhub\modules\space\widgets\SpacePickerField::widget([
    'form' => $form,
    'model' => $model,
    'attribute' => 'defaultSpaceGuid',
    'selection' => $model->defaultSpaces
]) ?>

<?= $form->field($model, 'defaultVisibility')->dropDownList($visibilityOptions) ?>

<?= $form->field($model, 'defaultJoinPolicy')->dropDownList($joinPolicyOptions, ['disabled' => $model->defaultVisibility == 0]) ?>

<?= $form->field($model, 'defaultContentVisibility')->dropDownList($contentVisibilityOptions, ['disabled' => $model->defaultVisibility == 0]) ?>

<?= $form->field($model, 'defaultIndexRoute')->dropDownList($indexModuleSelection) ?>

<?= $form->field($model, 'defaultIndexGuestRoute')->dropDownList($indexModuleSelection) ?>

<?= $form->field($model, 'defaultHideMembers')->checkbox() ?>

<?= $form->field($model, 'defaultHideActivities')->checkbox() ?>

<?= $form->field($model, 'defaultHideAbout')->checkbox() ?>

<?= $form->field($model, 'defaultHideFollowers')->checkbox() ?>

<?= Button::primary(Yii::t('base', 'Save'))->submit(); ?>

<?php ActiveForm::end(); ?>

<?= Html::beginTag('script'); ?>
$('#spacesettingsform-defaultvisibility').on('change', function () {
if (this.value == 0) {
$('#spacesettingsform-defaultjoinpolicy, #spacesettingsform-defaultcontentvisibility').val('0').prop('disabled', true);
} else {
$('#spacesettingsform-defaultjoinpolicy, #spacesettingsform-defaultcontentvisibility').val('0').prop('disabled', false);
}
});
<?= Html::endTag('script'); ?>
