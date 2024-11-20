<?php

use yii\helpers\Html;

humhub\modules\like\assets\LikeAsset::register($this);

?>

<span class="likeLinkContainer" id="likeLinkContainer_<?= $id ?>">

    <?php if (Yii::$app->user->isGuest): ?>

        <?= Html::a(Yii::t('LikeModule.base', 'Like'), Yii::$app->user->loginUrl, ['data-target' => '#globalModal']); ?>
    <?php else: ?>

        <a href="#" data-action-click="like.toggleLike" data-action-url="<?= $likeUrl ?>"
            class="like likeAnchor<?= !$canLike ? ' disabled' : '' ?>"
            style="<?= (!$currentUserLiked) ? '' : 'display:none' ?>">
            <i class="fa fa-heart"></i>
            <span class="likeCount tt" data-placement="top" data-toggle="tooltip"
                title="<?= $title ?>"><?= count($likes) ?></span>
        </a>
        <a href="#" data-action-click="like.toggleLike" data-action-url="<?= $unlikeUrl ?>"
            class="unlike likeAnchor<?= !$canLike ? ' disabled' : '' ?>"
            style="<?= ($currentUserLiked) ? '' : 'display:none' ?>">
            <i class="fa fa-heart"></i>
            <span class="likeCount tt" data-placement="top" data-toggle="tooltip"
                title="<?= $title ?>"><?= count($likes) ?></span>
        </a>
    <?php endif; ?>

</span>


