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
use humhub\modules\wiki\models\Vote;


/* @var WikiPage $wikiPage */
?>


<!-- 
<?php if ($wikiPage instanceof WikiPage): ?>
<div class="max-w-[40px]">
        tran van thien
        <h1>tran van thien</h1>
</div>
<?php endif; ?> -->

<!-- <?php
/** @var WikiPage $wikiPage */
?>

<div>
    <h3>Wiki Page: <?= Html::encode($wikiPage->title) ?></h3>
    <p>Updated at: <?= Yii::$app->formatter->asDatetime($wikiPage->content->updated_at) ?></p>
    <p>Updated by: <?= Html::encode($wikiPage->content->updatedBy->displayName ?? 'Unknown') ?></p>
</div>
 -->


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

$js = <<<JS
$(document).ready(function () {
    $(document).on("click", ".click_vote_islove", function () {
        var str = $(this).attr("id")
        // console.log("ID clicked:",id); // Debug ID của wikiPage
        const id = str.match(/StarValue_(\d+)/);
        const page_id = id[1];
        console.log(page_id);
        
        $.ajax({
            url: window.location.href, // Gửi request về chính file PHP hiện tại
            type: "POST",
            data: {
                value_love: "1",
                page_id: page_id,
                _csrf: yii.getCsrfToken() // Lấy CSRF Token trong HumHub
            },
            success: function (response) {
                console.log("Server response:", response);
                const match = response.match(/"active_islove":(\d+)/);
                if (match[1] == 1) {
                    // $("#upvote").addClass("bg-green-500");
                    const id_change = '#'+ str;
                    $(id_change).html("&#9733;"); // Sao đen (đã thích)
                } else {
                    const id_change = '#'+ str;
                    $(id_change).html("&#9734;"); // Sao trắng (bỏ thích)
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText); // Xem lỗi cụ thể từ server
                alert("Có lỗi xảy ra!");
            }
        });
    });
});
JS;
$this->registerJs($js, \yii\web\View::POS_READY);
?>


<?php
$userId = Yii::$app->user->id;
$vote = Vote::find()
    ->where(['user_id' => $userId, 'forum_id' => $wikiPage->id])
    ->one();
$class_star = "&#9734";

if ($vote !== null && $vote->is_love == 1) {
    $class_star = "&#9733";
} else {
    $class_star = "&#9734";

}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['value_love']) && isset($_POST['page_id'])) {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; // Đặt định dạng JSON
    $forumId = Yii::$app->request->post('page_id');
    $value_love = Yii::$app->request->post('value_love');
    $vote = Vote::find()->where(['user_id' => $userId, 'forum_id' => $forumId])->one();
    if ($vote) {
        $vote->is_love = $vote->is_love == 1 ? 0 : 1;
    } else {
        $vote = new Vote();
        $vote->user_id = $userId;
        $vote->forum_id = $forumId;
        $vote->is_vote = 0;
        $vote->value_vote = 0;
        $vote->is_love = 1; // Mặc định là thích
    }
    if ($vote->save()) {
        echo json_encode(['success' => true, 'active_islove' => $vote->is_love]);
    } else {
        echo json_encode(['success' => false]);
        return 0;
    }
    exit;
}
?>


<!-- 
<div class="max-w-[40px] w-[40px]">
<h3><?= Html::encode($wikiPage->id) ?></h3>
</div> -->

<div class="flex flex-wrap justify-between items-center gap-0">
    <div class="min-w-[20px] text-center text-[20px]">
        <?php echo '<div style="font-size: 20px; display:block; width:20px; "> ' . isset($forumVote) ? $forumVote->total_vote : 0 . '</div>'; ?>
    </div>
    <?php echo '<div id="StarValue_' . $wikiPage->id . '" class="click_vote_islove text-center cursor-pointer" style="font-size: 20px; display:block;">' . $class_star . '</div>'; ?>
</div>