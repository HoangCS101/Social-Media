<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\libs\Html;
use humhub\modules\wiki\helpers\Url;
use humhub\modules\wiki\models\WikiPage;
use humhub\widgets\Link;
use humhub\widgets\TimeAgo;
use humhub\modules\wiki\models\ForumVote;


/* @var WikiPage $wikiPage */
?>


<?php
$forumVote = ForumVote::findOne(['forum_id' => $wikiPage->id]);

if ($forumVote) {
 
} else {
    $forumVote = new ForumVote();
    $forumVote->forum_id = $wikiPage->id; // Gán giá trị cho forum_id
    $forumVote->total_vote = 0; // Gán giá trị cho total_vote
    $forumVote->updated_at = date('Y-m-d H:i:s'); // Gán giá trị cho thời gian cập nhật
    if ($forumVote->save()) {
        echo "Lưu thành công!";
    } else {
        echo "Lưu thất bại!";
    }
    echo "Không tìm thấy dữ liệu!";
}

?>

<?php if ($wikiPage instanceof WikiPage): ?>
    <div class=" flex flex-wrap">
        <div class="min-w-[36px] flex flex-wrap justify-between items-center">
            <div class="min-w-[20px] text-center text-[20px]">
            <?php echo '<div style="font-size: 20px; display:block; width:20px; "> ' . isset($forumVote) ? $forumVote->total_vote : 0 . '</div>'; ?>
            </div>
        <?php echo '<div class="text-center" style="font-size: 20px; display:block; padding-left:5px;"> &#9734;</div>'; // Ngôi sao lớn ?>
        </div>

        <div class="pl-4">
            <strong
                class="wiki-page-list-row-title text-[16px] py-2 font-semibold opacity-80"><?= Link::to($wikiPage->title, Url::toWiki($wikiPage)) ?>
            </strong>
            <div class="wiki-page-list-row-details">
                <?= TimeAgo::widget([
                    'timestamp' => $wikiPage->content->updated_at,
                ]) ?>
                <?php if ($wikiPage->content->updatedBy): ?>
                    &middot; <?= Html::encode($wikiPage->content->updatedBy->displayName) ?>
                <?php endif; ?>
                &middot; <?= Link::to(Yii::t('WikiModule.base', 'show changes'), Url::toWikiHistory($wikiPage)) ?>
            </div>
        </div>
    </div>
<?php endif; ?>