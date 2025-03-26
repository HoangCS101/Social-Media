<?php

use humhub\libs\Html;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\ui\form\widgets\ContentHiddenCheckbox;
use humhub\modules\ui\form\widgets\ContentVisibilitySelect;
use humhub\modules\ui\view\components\View;
use humhub\modules\wiki\assets\Assets;
use humhub\modules\wiki\models\forms\PageEditForm;
use humhub\modules\wiki\widgets\WikiEditor;
use humhub\modules\ui\form\widgets\ActiveForm;
use humhub\modules\wiki\widgets\WikiLinkModal;
use humhub\modules\wiki\widgets\WikiContent;
use humhub\modules\wiki\widgets\WikiMenu;
use humhub\modules\wiki\widgets\WikiPagePicker;
use humhub\modules\wiki\widgets\WikiPath;
use humhub\widgets\Button;
use humhub\modules\topic\widgets\TopicPicker;

/* @var $this View */
/* @var $model PageEditForm */
/* @var $contentContainer ContentContainerActiveRecord */
/* @var $requireConfirmation bool */
/* @var $displayFieldCategory bool */
/* @var $diffUrl string */
/* @var $discardChangesUrl string */

Assets::register($this);
?>

<div class="panel panel-default">
    <!-- <?= WikiPath::widget(['page' => $model->page]) ?> -->
    <div class="panel-body">
        <div class="row<?= $model->page->isCategory ? ' wiki-category-page-edit' : '' ?>">
            <?php WikiContent::begin([
                'id' => 'wiki-page-edit',
                'cssClass' => 'wiki-page-content'
            ]) ?>

            <div class="wiki-headline flex justify-between px-3">
                <div class="wiki-page-title text-2xl font-bold text-gray-800 tracking-wide">Create new topic</div>

                <div class="wiki-headline-top">
                    <?php if (!$requireConfirmation): ?>
                        <?= WikiMenu::widget([
                            'object' => $model->page,
                            'buttons' => $model->page->isNewRecord ? [] : WikiMenu::LINK_EDIT_SAVE,
                            'edit' => true
                        ]) ?>
                    <?php endif; ?>
                </div>
            </div>


            <?php $form = ActiveForm::begin(
                [
                    'enableClientValidation' => false,
                    'options' => [
                        'data-ui-widget' => 'wiki.Form',
                        'data-change-category-confirm' => Yii::t('WikiModule.base', 'Are you really sure? All existing category page assignments will be removed!'),
                        'data-is-category' => $model->page->isCategory,
                        'data-ui-init' => '1'
                    ],
                    'acknowledge' => true
                ]
            ); ?>
            <?= $form->field($model, 'latestRevisionNumber')->hiddenInput()->label(false); ?>
            <?php if ($requireConfirmation): ?>
                <div class="alert alert-danger">
                    <?= Yii::t(
                        'WikiModule.base',
                        '<strong>Warning!</strong><br><br>Another user has updated this page since you have started editing it. Please confirm that you want to overwrite those changes.<br>:linkToCompare',
                        [
                            ':linkToCompare' => Button::asLink('<i class="fa fa-arrow-right"></i>&nbsp;' . Yii::t('WikiModule.base', 'Compare changes'))->action('compareOverwriting', $diffUrl)->cssClass('colorDanger')
                        ]
                    ); ?>
                </div>
                <?= $form->field($model, 'backOverwriting')->hiddenInput()->label(false); ?>
                <?= $form->field($model, 'confirmOverwriting')->checkbox()->label(); ?>

                <?= Button::save(Yii::t('WikiModule.base', 'Overwrite'))->submit() ?>

                <?= Button::defaultType(Yii::t('WikiModule.base', 'Back'))->action('backOverwriting')->icon('back')->loader(false); ?>

                <div class="pull-right">
                    <?= Button::danger(Yii::t('WikiModule.base', 'Discard my changes'))->link($discardChangesUrl)->icon('close')->loader(true); ?>
                </div>
            <?php else: ?>
                <?= $form->field($model, 'confirmOverwriting')->hiddenInput()->label(false); ?>
            <?php endif; ?>

            <div<?php if ($requireConfirmation): ?> style="display:none" <?php endif; ?>>
                
                <?= $form->field($model->page, 'title')
                    ->textInput([
                        'placeholder' => Yii::t('WikiModule.base', 'New topic title'),
                        'disabled' => $model->isDisabledField('title'),
                        'class' => 'w-full p-3 rounded-lg border border-gray-300 bg-white text-gray-900'
                    ])->label(false); ?>

                <?= $form->field($model->revision, 'content')
                    ->widget(WikiEditor::class, [
                        'options' => [
                        ]
                    ])->label(false); ?>

                <?= Button::save()->submit(['class' => 'bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg']) ?>

        </div>

        <?php ActiveForm::end(); ?>

        <?php WikiContent::end() ?>

    </div>
</div>
</div>

<?= WikiLinkModal::widget(['contentContainer' => $contentContainer]) ?>

<script <?= Html::nonce() ?>>
    $('input[name="WikiPage[is_container_menu]"]').click(function () {
        $('#container_menu_order_field').toggle($(this).prop('checked'));
    })
</script>