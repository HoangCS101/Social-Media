<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\assets\CardsAsset;
use humhub\libs\Html;
use humhub\modules\space\components\SpaceDirectoryQuery;
use humhub\modules\space\widgets\SpaceDirectoryCard;
use humhub\modules\space\widgets\SpaceDirectoryFilters;
use yii\web\View;

/* @var $this View */
/* @var $spaces SpaceDirectoryQuery */

CardsAsset::register($this);
?>
<div class="main-header">
    <div class="content-bg-wrap bg-group"></div>
    <div class="container">
        <div class="row">
            <div class="col col-lg-8 m-auto col-md-8 col-sm-12 col-12">
                <div class="panel panel-default">

                    <div class="panel-heading">
                        <?= Yii::t('SpaceModule.base', '<strong>Spaces</strong>'); ?>
                    </div>

                    <div class="panel-body">
                        <?= SpaceDirectoryFilters::widget(); ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<div class="grid gap-4 xs:grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 mb-12">
    <?php if (!$spaces->exists()): ?>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <strong><?= Yii::t('SpaceModule.base', 'No results found!'); ?></strong><br />
                    <?= Yii::t('SpaceModule.base', 'Try other keywords or remove filters.'); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php foreach ($spaces->with('contentContainerRecord')->all() as $space) : ?>
        <?= SpaceDirectoryCard::widget(['space' => $space]); ?>
    <?php endforeach; ?>
</div>

<?php if (!$spaces->isLastPage()) : ?>
    <?= Html::tag('div', '', [
        'class' => 'cards-end',
        'data-current-page' => $spaces->pagination->getPage() + 1,
        'data-total-pages' => $spaces->pagination->getPageCount(),
    ]) ?>
<?php endif; ?>
