<?php

use humhub\libs\Html;
use humhub\modules\content\widgets\richtext\RichText;
use humhub\modules\file\widgets\ShowFiles;
use humhub\modules\mail\models\MessageEntry;
use humhub\modules\mail\models\SecureMessageEntry;
use humhub\modules\mail\helpers\Url;
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
    <div class="media flex-column pt-0 mt-0 <?= $isOwnMessage ? 'items-end' : 'items-start' ?>">
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
                        <p style="color: #000; width: auto; font-size: 16px"><?= Html::encode($content) ?></p>
                    <?php else : ?>
                        <p style="color: #fff; width: auto; font-size: 16px"><?= Html::encode($content) ?></p>
                    <?php endif; ?>
                    <?= ShowFiles::widget(['object' => $entry]) ?>
                </div>
            </div>
        </div>
        <div class="flex items-center"> 
            <?php if ($isOwnMessage) : ?>
                <?php if ($secure) : ?>
                    <?php if ($entry->status === 'pending') : ?>
                        <p style="color: blue; font-size: 12px; width: auto"><?= Html::encode('Pending') ?></p>
                    <?php elseif ($entry->status === 'failed') : ?>
                        <?= MessageEntryTime::widget(['entry' => $entry, 'options' => ['class' => 'ml-auto text-[12px]']]) ?>
                        <p style="color: red; font-size: 12px; width: auto; margin: 0px 4px"><?= Html::encode('- Failed') ?></p>
                        <!-- <div class="flex gap-1 text-[12px] ml-2">
                            <span 
                                class="text-blue-500 cursor-pointer hover:underline" 
                                data-action-click="resend"
                                data-action-url="<?= Url::to(['/message/resend', 'id' => $entry->id]) ?>">
                                Resend
                            </span>
                            <span class="text-blue-500 cursor-pointer hover:underline" onclick="deleteMessage(<?= $entry->id ?>)">Delete</span>
                        </div> -->
                    <?php else : ?>
                        <?= MessageEntryTime::widget(['entry' => $entry, 'options' => ['class' => 'ml-auto text-[12px]']]) ?>
                        <p style="color: green; font-size: 12px; width: auto"><?= Html::encode(' - Saved') ?></p>
                    <?php endif; ?>
                <?php else : ?>
                    <?= MessageEntryTime::widget(['entry' => $entry, 'options' => ['class' => 'ml-auto text-[12px]']]) ?>
                <?php endif; ?>

            <?php else : ?>
                <?= MessageEntryTime::widget(['entry' => $entry, 'options' => ['class' => 'mr-auto text-[12px]']]) ?>
            <?php endif; ?>
        </div>


    </div>
</div>
<?= Html::endTag('div') ?>
