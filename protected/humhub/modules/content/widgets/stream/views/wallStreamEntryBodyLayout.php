<?php

use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\widgets\WallEntryLabels;
use humhub\modules\topic\models\Topic;
use humhub\modules\topic\widgets\TopicLabel;
use humhub\modules\ui\view\components\View;

/* @var $this View */
/* @var $model ContentActiveRecord */
/* @var $header string */
/* @var $content string */
/* @var $footer string */
/* @var $topics Topic[] */

?>

<div class="panel panel-default wall_<?= $model->getUniqueId() ?>">
    <article class="hentry post">
        <div class="post__author author vcard inline-items">
            <?= $header ?>
        </div>

        <div class="wall-entry-body font-semibold p-0">
            <div class="topic-label-list text-black">
                <?php foreach ($topics as $topic) : ?>
                    <?= TopicLabel::forTopic($topic) ?>
                <?php endforeach; ?>
            </div>

            <div class="wall-entry-content content" id="wall_content_<?= $model->getUniqueId() ?>">
                <?= $content ?>
            </div>

            <div class="post-additional-info inline-items">
                <?= $footer ?>
            </div>
        </div>
        <!-- <div class="control-block-button post-control-button">

            <a href="#" class="btn btn-control">
                <svg class="olymp-like-post-icon">
                    <use xlink:href="svg-icons/sprites/icons.svg#olymp-like-post-icon"></use>
                </svg>
            </a>

            <a href="#" class="btn btn-control">
                <svg class="olymp-comments-post-icon">
                    <use xlink:href="svg-icons/sprites/icons.svg#olymp-comments-post-icon"></use>
                </svg>
            </a>

            <a href="#" class="btn btn-control">
                <svg class="olymp-share-icon">
                    <use xlink:href="svg-icons/sprites/icons.svg#olymp-share-icon"></use>
                </svg>
            </a>

        </div> -->
    </article>
</div>
