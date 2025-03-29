<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2022 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\libs\Html;
use humhub\modules\wiki\models\WikiPage;
use humhub\widgets\GridView;
use yii\data\ActiveDataProvider;
use humhub\modules\wiki\models\ForumVote;


/* @var $dataProvider ActiveDataProvider */
/* @var $options array */
?>
<?= Html::beginTag('div', $options) ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'summary' => '',
    'showHeader' => false,
    'layout' => "{items}\n<div class='pagination-container'>{pager}</div>",
    'columns' => [
        [
            'attribute' => 'custom_text',
            'format' => 'raw',
            'contentOptions' => ['class' => 'w-[75px]'],
            'value' => function (WikiPage $data) {
            // echo "<pre>";
            // var_dump($data->toArray());
            // echo "</pre>";
            return $this->render('test', ['wikiPage' => $data]);
        },
        ],
        [
            'attribute' => 'title',
            'format' => 'raw',
            'contentOptions' => ['class' => 'flex-1'],
            'value' => function (WikiPage $data) {
            // echo "<pre>";
            // var_dump($model->toArray()); 
            // echo "</pre>";
            return $this->render('wikiListTableRow', ['wikiPage' => $data]);
        },
        ],
    ],
]) ?>
<?= Html::endTag('div') ?>