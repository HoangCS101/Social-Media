<?php

use humhub\modules\space\widgets\Image;
use humhub\widgets\PanelMenu;
use yii\helpers\Html;

?>
<?php if (count($spaces) > 0) { ?>
    <div id="user-spaces-panel" class="panel panel-default members" style="position: relative;">

        <!-- Display panel menu widget -->
        <?php echo PanelMenu::widget(['id' => 'user-spaces-panel']); ?>

        <div class="panel-heading">
            <?php echo Yii::t('UserModule.base', 'Member of these Spaces'); ?>
        </div>

        <div class="panel-body">
            <ul class="widget w-friend-pages-added notification-list friend-requests">
                <?php

                foreach ($spaces as $space) { ?>
                    <li class="inline-items">
                        <div class="author-thumb pt-[10px]">
                        <?php
                    echo Image::widget([
                        'space' => $space,
                        'width' => 24,
                        'htmlOptions' => [
                            'class' => 'current-space-image',
                        ],
                        'link' => 'true',
                        'linkOptions' => [
                            'class' => 'tt',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => $space->name,
                        ]
                    ]);
                    ?>
                        </div>
                        <div class="notification-event">
                            <a href="index.php?r=space%2Fspace&cguid=<?= $space->guid ?>" class="h6 notification-friend">
                                <?php echo $space->name ?> </a>
                            <span class="chat-message-item"> <?php echo $space->description ?> </span>
                        </div>
                        <span class="notification-icon" data-toggle="tooltip" data-placement="top"
                            data-original-title="ADD TO YOUR FAVS">
                            <a href="#">
                                <svg class="olymp-star-icon">
                                    <use xlink:href="svg-icons/sprites/icons.svg#olymp-star-icon"></use>
                                </svg>
                            </a>
                        </span>

                    </li>
                    <?php
                }
                ?>
                <li class="inline-items">
                    <a href="index.php?r=space%2Fspaces" class="text-center text-[12px] text-center opacity-80">
                        <p class="text-center"> More Spaces</p>
                    </a>

                </li>

            </ul>

            <?php if ($showMoreLink): ?>
                <br>
                <br>
                <?= Html::a('Show all', $user->createUrl('/user/profile/space-membership-list'), ['class' => 'pull-right btn btn-sm btn-default', 'data-target' => '#globalModal']); ?>
            <?php endif; ?>
        </div>
    </div>
<?php } ?>