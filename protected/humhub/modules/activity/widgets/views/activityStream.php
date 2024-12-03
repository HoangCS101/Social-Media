<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.org/licences
 */

use humhub\modules\activity\assets\ActivityAsset;
use humhub\widgets\PanelMenu;
use yii\helpers\Html;

/* @var $this humhub\modules\ui\view\components\View */
/* @var $streamUrl string */
/* @var $options array */

ActivityAsset::register($this);
?>
<div class="panel panel-default panel-activities rounded-[20px] overflow-hidden" id="panel-activities">
    <?= PanelMenu::widget(['id' => 'panel-activities']) ?>
    <div class="panel-heading py-[16px] pl-5 border-b border-[#e6ecf5]">
        <?= Yii::t('ActivityModule.base', '<strong class="text-[14px]">Activity Feed</strong>') ?>
    </div>
    <?= Html::beginTag('div', $options) ?>
    <ul id="activityContents" class="media-list activities" data-stream-content></ul>
    <?= Html::endTag('div') ?>
</div>