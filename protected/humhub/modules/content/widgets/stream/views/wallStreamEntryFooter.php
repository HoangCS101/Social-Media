<?php

use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\widgets\stream\WallStreamEntryOptions;
use humhub\modules\content\widgets\WallEntryAddons;
use humhub\modules\ui\view\components\View;

/* @var $this View */
/* @var $model ContentActiveRecord */
/* @var $renderOptions WallStreamEntryOptions */
// debug_print_backtrace();
?>

<?php if (!$renderOptions->isAddonsDisabled()) : ?>

    <div class="stream-entry-addons clearfix">
        <?= WallEntryAddons::widget(['object' => $model, 'renderOptions' => $renderOptions]) ?>
    </div>

<?php endif; ?>
