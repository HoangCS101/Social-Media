<?php

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\user\widgets\HeaderControlsMenu;
use humhub\modules\user\widgets\ProfileHeaderControls;
use humhub\modules\friendship\widgets\FriendshipButton;
use humhub\modules\user\widgets\ProfileEditButton;
use humhub\modules\user\widgets\ProfileHeaderCounterSet;
use humhub\modules\user\widgets\UserFollowButton;

/* @var $container ContentContainerActiveRecord */

$controls = [[ProfileEditButton::class, ['user' => $container]]];
if (!FriendshipButton::isVisibleForUser($container)) {
    // If friendship is enabled the Follow button is visible inside the HeaderControlsMenu
    $controls[] = [UserFollowButton::class, ['user' => $container]];
}
$controls[] = [FriendshipButton::class, ['user' => $container]];
$controls[] = [HeaderControlsMenu::class, ['user' => $container]];
?>
<div class="panel-body">
    <div class="row">
        <div class="col col-lg-5 col-md-5 col-sm-5 col-5">
            <?= ProfileHeaderCounterSet::widget(['user' => $container]); ?>
        </div>
        <div class="col col-lg-5 ml-auto col-md-5 col-sm-5 col-5">
            <div class="controls controls-header pull-right">
                <?= ProfileHeaderControls::widget([
                    'user' => $container,
                    'widgets' => $controls
                ]) ?>
            </div>
        </div>
    </div>
</div>
