<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2022 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\models\Content;
use humhub\modules\file\handler\BaseFileHandler;
use humhub\modules\file\widgets\FilePreview;
use humhub\modules\topic\widgets\TopicPicker;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\modules\user\widgets\UserPickerField;
use humhub\modules\file\widgets\UploadButton;
use humhub\modules\file\widgets\FileHandlerButtonDropdown;
use humhub\modules\file\widgets\UploadProgress;
use humhub\widgets\Link;
use humhub\widgets\Button;
use yii\helpers\Html;

/* @var $submitUrl string */
/* @var $submitButtonText string */
/* @var $fileHandlers BaseFileHandler[] */
/* @var $canSwitchVisibility bool */
/* @var $contentContainer ContentContainerActiveRecord */
/* @var $pickerUrl string */
/* @var $scheduleUrl string */
?>

<div id="notifyUserContainer" class="form-group" style="margin-top:15px;display:none">
    <?= UserPickerField::widget([
        'id' => 'notifyUserInput',
        'url' => $pickerUrl,
        'formName' => 'notifyUserInput',
        'maxSelection' => 10,
        'disabledItems' => [Yii::$app->user->guid],
        'placeholder' => Yii::t('ContentModule.base', 'Add a member to notify'),
    ]) ?>
</div>

<div id="postTopicContainer" class="form-group" style="margin-top:15px;display:none">
    <?= TopicPicker::widget([
        'id' => 'postTopicInput',
        'name' => 'postTopicInput',
        'contentContainer' => $contentContainer
    ]); ?>
</div>

<?= Html::hiddenInput('containerGuid', $contentContainer->guid); ?>
<?= Html::hiddenInput('containerClass', get_class($contentContainer)); ?>

<div class="contentForm_options">
    <hr>
    <div class="btn_container">
        <?= Button::info($submitButtonText)->action('submit', $submitUrl)->id('post_submit_button')->submit() ?>

        <?php $uploadButton = UploadButton::widget([
            'id' => 'contentFormFiles',
            'tooltip' => Yii::t('ContentModule.base', 'Attach Files'),
            'progress' => '#contentFormFiles_progress',
            'preview' => '#contentFormFiles_preview',
            'dropZone' => '#contentFormBody',
            'max' => Yii::$app->getModule('content')->maxAttachedFiles
        ]); ?>
        <?= FileHandlerButtonDropdown::widget(['primaryButton' => $uploadButton, 'handlers' => $fileHandlers, 'cssButtonClass' => 'btn-default']); ?>

        <!-- public checkbox -->
        <?= Html::checkbox('visibility', '', ['id' => 'contentForm_visibility', 'class' => 'contentForm hidden', 'aria-hidden' => 'true']); ?>

        <!-- state data -->
        <?= Html::hiddenInput('state', Content::STATE_PUBLISHED) ?>

        <!-- content sharing -->
        <div class="pull-right">
            <span class="label-container">
                <span class="label label-info label-public hidden"><?= Yii::t('ContentModule.base', 'Public'); ?></span>
            </span>

            <ul class="nav nav-pills preferences" style="right:0;top:5px">
                <li class="dropdown">
                    <a class="dropdown-toggle" style="padding:5px 10px" data-toggle="dropdown" href="#"
                       aria-label="<?= Yii::t('base', 'Toggle post menu'); ?>" aria-haspopup="true">
                        <?= Icon::get('cogs') ?>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <?= Link::withAction(Yii::t('ContentModule.base', 'Notify members'), 'notifyUser')->icon('bell') ?>
                        </li>
                        <?php if (TopicPicker::showTopicPicker($contentContainer)) : ?>
                            <li>
                                <?= Link::withAction(Yii::t('ContentModule.base', 'Topics'), 'setTopics')->icon(Yii::$app->getModule('topic')->icon) ?>
                            </li>
                        <?php endif; ?>
                        <?php if ($canSwitchVisibility): ?>
                            <li>
                                <?= Link::withAction(Yii::t('ContentModule.base', 'Change to "Public"'), 'changeVisibility')
                                    ->id('contentForm_visibility_entry')->icon('unlock') ?>
                            </li>
                        <?php endif; ?>
                        <li>
                            <?= Link::withAction(Yii::t('ContentModule.base', 'Create as draft'), 'changeState')
                                ->icon('edit')
                                ->options([
                                    'data-state' => Content::STATE_DRAFT,
                                    'data-state-title' => Yii::t('ContentModule.base', 'Draft'),
                                    'data-button-title' => Yii::t('ContentModule.base', 'Save as draft')
                                ]) ?>
                        </li>
                        <li>
                            <?= Link::withAction(Yii::t('ContentModule.base', 'Schedule publication'), 'scheduleOptions', $scheduleUrl)
                                ->icon('clock-o') ?>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

    <?= UploadProgress::widget(['id' => 'contentFormFiles_progress']) ?>
    <?= FilePreview::widget(['id' => 'contentFormFiles_preview', 'edit' => true, 'options' => ['style' => 'margin-top:10px;']]); ?>
</div><!-- /contentForm_Options -->
