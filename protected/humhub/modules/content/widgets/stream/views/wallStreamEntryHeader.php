<?php

use humhub\libs\Html;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\models\Content;
use humhub\modules\content\widgets\ArchivedIcon;
use humhub\modules\content\widgets\HiddenIcon;
use humhub\modules\content\widgets\LockCommentsIcon;
use humhub\modules\content\widgets\StateBadge;
use humhub\modules\content\widgets\stream\WallStreamEntryOptions;
use humhub\modules\content\widgets\UpdatedIcon;
use humhub\modules\content\widgets\VisibilityIcon;
use humhub\modules\content\widgets\WallEntryControls;
use humhub\modules\space\models\Space;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\modules\ui\view\components\View;
use humhub\widgets\TimeAgo;

/* @var $this View */
/* @var $model ContentActiveRecord */
/* @var $headImage string */
/* @var $permaLink string */
/* @var $title string */
/* @var $renderOptions WallStreamEntryOptions */

$container = $model->content->container;
?>

<div class="stream-entry-icon-list">
    <?php if ($model->content->isArchived()): ?>
        <?= ArchivedIcon::getByModel($model) ?>
    <?php elseif ($renderOptions->isPinned($model)): ?>
        <?= Icon::get('map-pin', ['htmlOptions' => ['class' => 'icon-pin tt', 'title' => Yii::t('ContentModule.base', 'Pinned')]]) ?>
    <?php endif; ?>
    <?= StateBadge::widget(['model' => $model]); ?>
</div>

<!-- since v1.2 -->
<div class="stream-entry-loader"></div>

<!-- start: show wall entry options -->
<?php if (!$renderOptions->isControlsMenuDisabled()): ?>
    <?= WallEntryControls::widget(['renderOptions' => $renderOptions, 'object' => $model, 'wallEntryWidget' => $this->context]) ?>
<?php endif; ?>
<!-- end: show wall entry options -->


<div class="wall-entry-header-image">
    <?= $headImage ?>
</div>

<div class="author-date ">

    <a class="h6 post__author-name fn text-[#515365] " href="#">
        <?= $title ?>

        <?php if ($renderOptions->isShowContainerInformationInTitle($model)): ?>
            <span class="viaLink text-[16px]">
                <?= Icon::get('caret-right') ?>
                <?= Html::containerLink($model->content->container) ?>
            </span>
        <?php endif; ?>
    </a>

    <div class="post__date flex flex-wrap font-mono font-thin opacity-50">
        <?php if ($renderOptions->isShowAuthorInformationInSubHeadLine($model)): ?>
            <?= Html::containerLink($model->content->createdBy, ['class' => 'wall-entry-container-link pr-2']) ?>
        <?php endif ?>
        <?php if ($renderOptions->isShowContainerInformationInSubTitle($model)): ?>
            <?php if ($renderOptions->isShowAuthorInformationInSubHeadLine($model)): ?>
                <?= Icon::get('caret-right') ?>
                <?= Html::containerLink($model->content->container, ['class' => 'pl-2 wall-entry-container-link ']) ?>
            <?php elseif ($model->content->container instanceof Space): ?>
                <?= Html::containerLink($model->content->container, ['class' => 'pl-2 wall-entry-container-link ']) ?>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($renderOptions->isShowAuthorInformationInSubHeadLine($model) || $renderOptions->isShowContainerInformationInSubTitle($model)): ?>
            &middot;
        <?php endif; ?>

        <a class="" href="<?= $permaLink ?>">
            <?= TimeAgo::widget(['timestamp' => $model->content->created_at, 'titlePrefixInfo' => Yii::t('ContentModule.base', 'Created at:') . ' ']) ?>
        </a>

        &middot;

        <div class="wall-entry-icons pl-2">
            <?php if ($model->content->isUpdated()): ?>
                <?= UpdatedIcon::getByDated($model->content->updated_at) ?>
            <?php endif; ?>

            <?= VisibilityIcon::getByModel($model) ?>
            <?= HiddenIcon::getByModel($model) ?>
            <?= LockCommentsIcon::getByModel($model) ?>
        </div>
    </div>
</div>