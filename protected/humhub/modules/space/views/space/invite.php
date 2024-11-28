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
<?= HeaderControls::widget(['widgets' => [
    [InviteButton::class, ['space' => $container], ['sortOrder' => 10]],
    [MembershipButton::class, [
        'space' => $container,
        'options' => [
            'becomeMember' => ['mode' => 'link'],
            'acceptInvite' => ['mode' => 'link']
        ],
    ], ['sortOrder' => 20]],
    [FollowButton::class, [
        'space' => $container,
        'followOptions' => ['class' => 'btn btn-primary'],
        'unfollowOptions' => ['class' => 'btn btn-primary active']
    ], ['sortOrder' => 30]]
]]); ?>
<?= HeaderControlsMenu::widget(['space' => $container]); ?>