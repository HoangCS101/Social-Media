<?php

use humhub\widgets\PanelMenu;
use yii\helpers\Html;
use humhub\modules\user\models\User;

/* @var User[] $followers */
/* @var User[] $following */
?>
<?php if (count($followers) > 0) : ?>
    <div class="panel panel-default follower" id="profile-follower-panel">

        <!-- Display panel menu widget -->
        <?php echo PanelMenu::widget(['id' => 'profile-follower-panel']); ?>

        <div class="panel-heading"><?php echo Yii::t('UserModule.base', '<strong>Followers</strong>'); ?></div>

        <div class="panel-body">
            <?php foreach ($followers as $follower): ?>
                <a href="<?php echo $follower->getUrl(); ?>">
                    <img src="<?php echo $follower->getProfileImage()->getUrl(); ?>" class="img-rounded tt img_margin"
                         height="24" width="24" alt="24x24" data-src="holder.js/24x24"
                         style="width: 24px; height: 24px;"
                         data-toggle="tooltip" data-placement="top" title=""
                         data-original-title="<?php echo Html::encode($follower->displayName); ?>">
                </a>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (count($following) > 0) : ?>
    <div class="panel panel-default follower" id="profile-following-panel">

        <!-- Display panel menu widget -->
        <?php echo PanelMenu::widget(['id' => 'profile-following-panel']); ?>

        <div class="panel-heading">
            <?php echo Yii::t('UserModule.base', '<strong>Following</strong>'); ?>
        </div>

        <div class="panel-body">
            <?php foreach ($following as $followingUser): ?>
                <a href="<?php echo $followingUser->getUrl(); ?>">
                    <img src="<?php echo $followingUser->getProfileImage()->getUrl(); ?>"
                         class="img-rounded tt img_margin"
                         height="24" width="24" alt="24x24" data-src="holder.js/24x24"
                         style="width: 24px; height: 24px;"
                         data-toggle="tooltip" data-placement="top" title=""
                         data-original-title="<?php echo Html::encode($followingUser->displayName); ?>">
                </a>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
