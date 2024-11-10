<?php

use humhub\widgets\LoaderWidget;
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $options [] */

?>

<?= Html::beginTag('div', $options) ?>
<a class="" href="#" id="icon-notifications" data-action-click='toggle' x
    aria-label="<?= Yii::t('NotificationModule.base', 'Open the notification dropdown menu') ?>" data-toggle="dropdown">
    <div class="text-white">
        <i class="fa fa-bell"></i>
    </div>
</a>

<span id="badge-notifications" style="display:none;"
    class="label label-danger label-notifications relative top-[-10px] rounded-[50%] w-4 h-4"></span>

<div class="p-2">

</div>
<!-- container for ajax response -->
<ul id="dropdown-notifications" class="dropdown-menu w-[360px] top-[50px] left-[-100px] z-[1000] p-0">
    <div class="mCustomScrollbar" data-mcs-theme="dark">
        <div class="ui-block-title ui-block-title-small text-[10px] font-semibold">
            <div class="arrow"></div> <?= Yii::t('NotificationModule.base', 'Notifications'); ?>
            <div class="dropdown-header-link">
                <a id="mark-seen-link" data-action-click='markAsSeen'
                    data-action-url="<?= Url::to(['/notification/list/mark-as-seen']); ?>">
                    <?= Yii::t('NotificationModule.base', 'Mark all as seen'); ?>
                </a>
            </div>
            <a class="font-semibold" href="#">Mark all as read</a>
            <a class="font-semibold" href="#">Settings</a>
        </div>

        <!-- <a href="#" class="view-all bg-primary">View All Notifications</a> -->
    </div>
    <li>
        <ul class="media-list"></ul>
    </li>
    <li id="loader_notifications">
        <?= LoaderWidget::widget(); ?>
    </li>
    <li class="">
        <div class="dropdown-footer ">
            <a class="btn btn-default col-md-12 py-4 text-white" href="<?= Url::to(['/notification/overview']); ?>">
                <?= Yii::t('NotificationModule.base', 'Show all notifications'); ?>
            </a>
        </div>
    </li>

</ul>
<?= Html::endTag('div') ?>