<?php

use yii\helpers\Url;

$this->pageTitle = Yii::t('UserModule.auth', 'Registration successful');
?>
<div class="container" style="text-align: center;">
    <?= humhub\widgets\SiteLogo::widget(['place' => 'login']); ?>
    <br>
    <div class="row">
        <div class="panel panel-default" style="max-width: 300px; margin: 0 auto 20px; text-align: left;">
            <div
                class="panel-heading"><?php echo Yii::t('UserModule.auth', '<strong>Registration</strong> successful!'); ?></div>
            <div class="panel-body">
                <p><?php echo Yii::t('UserModule.auth', 'Please check your email and follow the instructions!'); ?></p>
                <br/>
                <a href="<?php echo Url::to(["/"]) ?>" data-pjax-prevent data-ui-loader
                   class="btn btn-primary"><?php echo Yii::t('UserModule.auth', 'back to home') ?></a>
            </div>
        </div>
    </div>
</div>



