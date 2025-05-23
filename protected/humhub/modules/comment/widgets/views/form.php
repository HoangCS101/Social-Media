<?php

use humhub\modules\comment\models\Comment;
use humhub\modules\content\Module;
use humhub\modules\content\widgets\richtext\RichTextField;
use humhub\modules\file\handler\BaseFileHandler;
use humhub\modules\file\widgets\FileHandlerButtonDropdown;
use humhub\modules\file\widgets\FilePreview;
use humhub\modules\file\widgets\UploadButton;
use humhub\modules\ui\form\widgets\ActiveForm;
use humhub\modules\ui\view\components\View;
use humhub\widgets\Button;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this View */
/* @var $objectModel string */
/* @var $objectId int */
/* @var $model Comment */
/* @var $id string unique object id */
/* @var $isNestedComment bool */
/* @var $contentModule Module */
/* @var $mentioningUrl string */
/* @var $isHidden bool */
/* @var $fileHandlers BaseFileHandler[] */

$contentModule = Yii::$app->getModule('content');
$submitUrl = Url::to(['/comment/comment/post']);

$placeholder = ($isNestedComment)
    ? Yii::t('CommentModule.base', 'Write a new reply...')
    : Yii::t('CommentModule.base', 'Write a new comment...');
?>

<div id="comment_create_form_<?= $id ?>" class="comment_create content_create" data-ui-widget="comment.Form"
     style="<?php if ($isHidden): ?>display:none<?php endif; ?>">

    <hr>

    <?php $form = ActiveForm::begin([
        'action' => $submitUrl,
        'acknowledge' => true,
    ]) ?>

    <?= Html::hiddenInput('objectModel', $objectModel) ?>
    <?= Html::hiddenInput('objectId', $objectId) ?>

    <div class="content-create-input-group">
        <?= $form->field($model, 'message')->widget(RichTextField::class, [
            'id' => 'newCommentForm_' . $id,
            'form' => $form,
            'layout' => RichTextField::LAYOUT_INLINE,
            'pluginOptions' => ['maxHeight' => '300px'],
            'mentioningUrl' => $mentioningUrl,
            'placeholder' => $placeholder,
            'events' => [
                'scroll-active' => 'comment.scrollActive',
                'scroll-inactive' => 'comment.scrollInactive'
            ]
        ])->label(false) ?>

        <div class="upload-buttons"><?php
            $uploadButton = UploadButton::widget([
                'id' => 'comment_create_upload_' . $id,
                'tooltip' => Yii::t('ContentModule.base', 'Attach Files'),
                'options' => ['class' => 'main_comment_upload'],
                'progress' => '#comment_create_upload_progress_' . $id,
                'preview' => '#comment_create_upload_preview_' . $id,
                'dropZone' => '#comment_create_form_' . $id,
                'max' => $contentModule->maxAttachedFiles,
                'cssButtonClass' => 'btn-sm btn-default pl-[10px] pr-0',
            ]);
            echo FileHandlerButtonDropdown::widget([
                'primaryButton' => $uploadButton,
                'handlers' => $fileHandlers,
                'cssButtonClass' => 'btn-sm btn-default',
                'pullRight' => true,
            ]);
            echo Button::info()
                ->icon('send')
                ->cssClass('btn-comment-submit')->sm()
                // ->style('padding: 8px 16px !important;')
                ->action('submit', $submitUrl)->submit();
            ?></div>
    </div>

    <div id="comment_create_upload_progress_<?= $id ?>" style="display:none;margin:10px 0px;"></div>

    <?= FilePreview::widget([
        'id' => 'comment_create_upload_preview_' . $id,
        'options' => ['style' => 'margin-top:10px'],
        'edit' => true
    ]) ?>

    <?php ActiveForm::end() ?>
</div>
