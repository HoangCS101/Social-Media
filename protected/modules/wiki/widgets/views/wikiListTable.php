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
<?php
// $forumVotes = ForumVote::find()
//     ->orderBy(['total_vote' => SORT_DESC, 'forum_id' => SORT_ASC])
//     ->all();
// foreach ($forumVotes as $vote) {
//     echo "Forum ID: " . $vote->forum_id . ", Total Vote: " . $vote->total_vote . "<br>";
// }

$wikiPages11 = WikiPage::find()
    ->select(['wiki_page.*', 'COALESCE(forum_vote.total_vote, 0) AS total_vote'])
    ->leftJoin('forum_vote', 'wiki_page.id = forum_vote.forum_id')
    ->orderBy(['total_vote' => SORT_DESC, 'wiki_page.id' => SORT_ASC])
    ->asArray()
    ->one();
?>


<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'summary' => '',
    'showHeader' => false,
    'layout' => "{items}\n<div class='pagination-container'>{pager}</div>",
    'columns' => [
        [
            'attribute' => 'title',
            'format' => 'raw',
            'value' => function (WikiPage $model) {
                // echo "<pre>";
                // var_dump($model->toArray()); 
                // echo "</pre>";
                return $this->render('wikiListTableRow', ['wikiPage' => $model]);
            },
        ],
    ],
]) ?>
<?= Html::endTag('div') ?>