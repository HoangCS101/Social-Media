<?php
use humhub\libs\Html;
use humhub\modules\topic\models\Topic;
use humhub\modules\topic\widgets\TopicLabel;
use humhub\modules\wiki\widgets\WikiRichText;
use humhub\widgets\Button;
use humhub\modules\wiki\helpers\Url;
use humhub\modules\wiki\models\WikiPage;
use humhub\modules\wiki\models\ForumVote;
use yii\web\Response;
use humhub\modules\wiki\models\Vote;


/* @var $this \humhub\modules\ui\view\components\View */
/* @var $page \humhub\modules\wiki\models\WikiPage */
/* @var $canEdit bool */
/* @var $content string */
?>
<?php
// $vote = Vote::find()->all();
// var_dump($vote);
$userId = Yii::$app->user->id;
$vote = Vote::find()
    ->where(['user_id' => $userId, 'forum_id' => $page->id])
    ->one();
$checkactive = $vote ? $vote->value_vote : 2;
$js = <<<JS
$(document).ready(function () {
    console.log("Script đã được load!");

    let check = {$checkactive}
if (check === 1) {
    $("#upvote").addClass("bg-green-500");
    $("#downvote").removeClass("bg-red-500");
}else if(check === 2 || check === 0) 
{
    $("#upvote").removeClass("bg-green-500");
    $("#downvote").removeClass("bg-red-500");
}else {
    $("#upvote").removeClass("bg-green-500");
    $("#downvote").addClass("bg-red-500");
}

    $("#upvote, #downvote").click(function () {
        let type = $(this).attr("id"); // "upvote" hoặc "downvote"
        let forumId = {$page->id}; // ID của bài viết
      
        $.ajax({
            url: "", // Gửi request về chính file PHP hiện tại
            type: "POST",
            data: {
                forum_id: forumId,
                type: type,
                _csrf: yii.getCsrfToken() // Lấy CSRF Token trong HumHub
            },
            success: function (response) {(response)
                console.log(response)
                var newVote;
                let match = response.match(/"newvote":(-?\d+)}/); 
if (match) {
 newVote = parseInt(match[1], 10); // Lấy giá trị số
}        

const match1 = response.match(/"active":(-?\d+),/);
const activeValue = parseInt(match1[1], 10);
if (activeValue === 1) {
    $("#upvote").addClass("bg-green-500");
    $("#downvote").removeClass("bg-red-500");
}
if(activeValue === 0) 
{
    $("#upvote").removeClass("bg-green-500");
    $("#downvote").removeClass("bg-red-500");
}
if(activeValue === -1) {
    $("#upvote").removeClass("bg-green-500");
    $("#downvote").addClass("bg-red-500");
}
console.log(newVote,activeValue)
 $("#vote-count").text(newVote); 
            },
             error: function (xhr, status, error) {
             console.log(xhr.responseText); // Xem lỗi cụ thể từ server
               alert("Có lỗi xảy ra!");
    }
        });
    });
});
JS;

// Đăng ký đoạn JavaScript vào view
$this->registerJs($js, \yii\web\View::POS_READY);
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forum_id'], $_POST['type'])) {
    Yii::$app->response->format = Response::FORMAT_RAW;
    $forumId = Yii::$app->request->post('forum_id');
    $type = Yii::$app->request->post('type');

    $forumVote = ForumVote::findOne(['forum_id' => $forumId]);
    if ($forumVote === null) {
        $forumVote = new ForumVote();
        $forumVote->forum_id = $forumId; // Gán giá trị cho forum_id
        $forumVote->total_vote = 0; // Gán giá trị cho total_vote
        $forumVote->updated_at = date('Y-m-d H:i:s'); // Gán giá trị cho thời gian cập nhật
        if ($forumVote->save()) {
            echo "Lưu thành công!";
        } else {
            echo "Lưu thất bại!";
            print_r($forumVote->getErrors()); // Hiển thị lỗi nếu có
        }
    }
    // $vote = Vote::find()->all();
    $vote = Vote::find()
        ->where(['user_id' => $userId, 'forum_id' => $forumId])
        ->one();

    if ($vote) {
        if ($vote->is_vote === 0) {
            if ($type === 'upvote') {
                $vote->is_vote = 1;
                $vote->value_vote = 1;
                $vote->save();
                $forumVote = ForumVote::findOne(['forum_id' => $forumId]);
                $forumVote->total_vote += 1;
                $forumVote->save();
                echo json_encode(['success' => true, 'active' => 1, 'newvote' => $forumVote->total_vote]);
                exit;
                return;
            } else {
                $vote->is_vote = 1;
                $vote->value_vote = -1;
                $vote->save();
                $forumVote = ForumVote::findOne(['forum_id' => $forumId]);
                $forumVote->total_vote += -1;
                $forumVote->save();
                echo json_encode(['success' => true, 'active' => -1, 'newvote' => $forumVote->total_vote]);
                exit;
                return;
            }
        } else if ($vote->is_vote === 1) {
            if ($type === 'upvote' && $vote->value_vote === 1) {

                $vote->is_vote = 0;
                $vote->value_vote = 0;
                $vote->save();
                $forumVote = ForumVote::findOne(['forum_id' => $forumId]);
                $forumVote->total_vote += -1;
                $forumVote->save();
                echo json_encode(['success' => true, 'active' => 0, 'newvote' => $forumVote->total_vote]);
                exit;
                return;

            }
            if ($type === 'upvote' && $vote->value_vote === -1) {
                $vote->is_vote = 1;
                $vote->value_vote = 1;
                $vote->save();
                $forumVote = ForumVote::findOne(['forum_id' => $forumId]);
                $forumVote->total_vote += 2;
                $forumVote->save();
                echo json_encode(['success' => true, 'active' => 1, 'newvote' => $forumVote->total_vote]);
                exit;
                return;
            }
            if ($type === 'downvote' && $vote->value_vote === 1) {
                $vote->is_vote = 1;
                $vote->value_vote = -1;
                $vote->save();
                $forumVote = ForumVote::findOne(['forum_id' => $forumId]);
                $forumVote->total_vote += -2;
                $forumVote->save();
                echo json_encode(['success' => true, 'active' => -1, 'newvote' => $forumVote->total_vote]);
                exit;
                return;
            }
            if ($type === 'downvote' && $vote->value_vote === -1) {
                $vote->is_vote = 0;
                $vote->value_vote = 0;
                $vote->save();
                $forumVote = ForumVote::findOne(['forum_id' => $forumId]);
                $forumVote->total_vote += 1;
                $forumVote->save();
                echo json_encode(['success' => true, 'active' => 0, 'newvote' => $forumVote->total_vote]);
                exit;
                return;
            }
        }
    } else {
        $newvote = new Vote();
        $newvote->user_id = (int) $userId;
        $newvote->forum_id = (int) $forumId;
        $newvote->is_vote = 1;
        if ($type === 'upvote')
            $newvote->value_vote = 1;
        else
            $newvote->value_vote = -1;
        if ($newvote->save()) {
            echo "Thêm vote thành công!";
        } else {
            print_r($newvote->getErrors()); // In ra lỗi nếu có }
        }
        if (Yii::$app->request->isPost) {

            if (!$forumVote) {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy forum']);
                exit;
            }
            if ($type === 'upvote') {
                $forumVote->total_vote += 1;
            } elseif ($type === 'downvote') {
                $forumVote->total_vote -= 1;
            }
            $active = $type === 'upvote' ? 1 : 0;
            if ($forumVote->save()) {
                echo json_encode(['success' => true, 'active' => $active, 'newvote' => $forumVote->total_vote]);
                exit;

            } else {
                echo json_encode(['success' => false, 'active' => $active, 'newvote' => $forumVote->total_vote]);
                exit;
            }
        }
    }

}
$forumVote = ForumVote::findOne(['forum_id' => $page->id]);
?>


<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
    }
</style>

<div
    class="flex items-center space-x-3 mt-6 p-4 bg-gradient-to-r from-gray-200 to-gray-200 text-white rounded-lg text-[16px]">
    <!-- <h1 class="text-4xl font-extrabold">Topic:</h1> -->
    <p class="text-[20px] font-semibold opacity-100 "> <?= Html::encode($page->title) ?> </p>
</div>
<br>
<div class="flex flex-wrap gap-4">

    <div>
        <div class="bg-white p-6 flex flex-col items-center space-y-4">
            <!-- Upvote Button -->
            <button id="upvote"
                class="w-12 h-12 flex items-center justify-center rounded-full border-2 border-gray-400 text-gray-600 hover:bg-green-500 hover:text-white transition duration-100 text-2xl">
                ▲
            </button>

            <!-- Vote Count -->
            <p id="vote-count" class="text-4xl font-bold text-gray-800 transition duration-300">
                <?= isset($forumVote->total_vote) ? Html::encode($forumVote->total_vote) : '0'; ?>
            </p>

            <!-- Downvote Button -->
            <button id="downvote"
                class="w-12 h-12 flex items-center justify-center rounded-full border-2 border-gray-400 text-gray-600 hover:bg-red-500 hover:text-white transition duration-100 text-2xl">
                ▼
            </button>
        </div>

    </div>

    <div class="flex-1">
        <?= $this->render('_view_category_index', ['page' => $page]) ?>

        <?php if (!empty($content)): ?>
            <div class=" min-h-[212px] markdown-render bg-white rounded-lg p-4  transition-all duration-300 ease-in-out border-l-4 border-gray-200 text-lg leading-relaxed"
                data-ui-widget="wiki.Page" <?= $canEdit ? ' data-edit-url="' . Url::toWikiEdit($page) . '"' : '' ?>
                data-ui-init style="display:none">
                <?= WikiRichText::output($content, ['id' => 'wiki-page-richtext',]) ?>
            </div>
        <?php else: ?>
            <div class="mt-6 text-gray-700 italic text-xl text-center bg-yellow-100 p-6 rounded-lg shadow-md">
                <?= Yii::t('WikiModule.base', 'This topic is empty.') ?>
            </div>

            <?php if ($canEdit): ?>
                <div class="mt-6 text-center">
                    <?= Button::info(Yii::t('WikiModule.base', 'Edit page'))
                        ->link(Url::toWikiEdit(page: $page))
                        ->icon('fa-pencil-square-o')
                        ->addClass('bg-gradient-to-r from-green-400 to-green-600 text-white font-bold py-4 px-8 rounded-lg shadow-lg transition-all duration-300 ease-in-out text-xl')
                        ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- <p class="text-[20px] font-semibold opacity-100">
    <?= isset($userId) ? Html::encode($userId) : 'Không có User ID'; ?>
</p>


<?php
// $wikiPages = WikiPage::find()->all();


// foreach ($wikiPages as $page) {
//     echo "<p>Tiêu đề: " . Html::encode($page->title) . "</p>";
//     echo "<p>ID: " . $page->id . "</p>";
//     echo "<hr>";
// }
?> -->