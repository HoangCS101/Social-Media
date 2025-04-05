<?php

use humhub\libs\Html;
use humhub\modules\content\widgets\richtext\RichText;
use humhub\modules\file\widgets\ShowFiles;
use humhub\modules\mail\models\MessageEntry;
use humhub\modules\mail\models\SecureMessageEntry;
use humhub\modules\mail\widgets\ConversationDateBadge;
use humhub\modules\mail\widgets\ConversationEntryMenu;
use humhub\modules\mail\widgets\MessageEntryTime;
use humhub\modules\ui\view\components\View;
use humhub\modules\user\widgets\Image;

/* @var $this View */
/* @var $entry MessageEntry */
/* @var $options array */
/* @var $contentClass string */
/* @var $showUser bool */
/* @var $userColor string */
/* @var $showDateBadge bool */
/* @var $isOwnMessage bool */
?>
<?php if ($showDateBadge) : ?>
    <?= ConversationDateBadge::widget(['entry' => $entry]) ?>
<?php endif; ?>

<?= Html::beginTag('div', $options) ?>

<div class="d-flex flex-row">
    <?php if ($showUser) : ?>
        <span class="author-image pull-left mr-2">
            <?= Image::widget([
                'user' => $entry->user,
                'width' => 30,
            ]) ?>
        </span>
    <?php endif; ?>
    <div class="media flex-column pt-0 mt-0">
        <div class="d-flex flex-row <?= $isOwnMessage ? 'justify-end' : '' ?>">
            <?= ConversationEntryMenu::widget(['entry' => $entry]) ?>
            
            <div  
                class="<?= $contentClass ?>" 
                style="
                    background-color: <?= $showUser? '#fff': '#234dffcc'?> !important; 
                    min-width: 15px; 
                    max-width: 500px !important;
                "
            >
                <div class="markdown-render" style="min-width: 15px; max-width: 500px;">
                    <?php if (!$isOwnMessage) : ?>
                        <div class="author-label" style="color: <?= Html::encode($userColor) ?>">
                            <?= Html::encode($entry->user->displayName) ?>
                        </div>
                        <p style="color: #000; width: auto"><?= Html::encode($entry->content) ?></p>
                    <?php else : ?>
                        <p style="color: #fff; width: auto"><?= Html::encode($entry->content) ?></p>
                    <?php endif; ?>

                    <?= ShowFiles::widget(['object' => $entry]) ?>
                </div>
            </div>
        </div>
        <?php if ($isOwnMessage) : ?>
            <?= MessageEntryTime::widget(['entry' => $entry, 'options' => ['class' => 'ml-auto']]) ?>
        <?php else : ?>
            <?= MessageEntryTime::widget(['entry' => $entry, 'options' => ['class' => 'mr-auto']]) ?>
        <?php endif; ?>
    </div>
</div>
<?= Html::endTag('div') ?>
