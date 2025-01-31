<?php
/* @var $this View */

/* @var $container Space */

use humhub\modules\space\models\Space;
use humhub\modules\space\widgets\FollowButton;
use humhub\modules\space\widgets\HeaderControls;
use humhub\modules\space\widgets\HeaderControlsMenu;
use humhub\modules\space\widgets\HeaderCounterSet;
use humhub\modules\space\widgets\InviteButton;
use humhub\modules\space\widgets\MembershipButton;
use humhub\modules\ui\view\components\View;

?>

<div class="panel-body">
    <div class="panel-profile-controls">
        <div class="row">
            <div class="col-md-12">

                <div class="controls controls-header pull-right">
                    <?= HeaderControls::widget(['widgets' => [
                        [InviteButton::class, [
                            'space' => $container
                        ], ['sortOrder' => 10]],
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
                </div>
            </div>
        </div>
    </div>
</div>
