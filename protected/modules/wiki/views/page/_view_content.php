<?php

use humhub\libs\Html;
use humhub\modules\topic\models\Topic;
use humhub\modules\topic\widgets\TopicLabel;
use humhub\modules\wiki\widgets\WikiRichText;
use humhub\widgets\Button;
use humhub\modules\wiki\helpers\Url;

/* @var $this \humhub\modules\ui\view\components\View */
/* @var $page \humhub\modules\wiki\models\WikiPage */
/* @var $canEdit bool */
/* @var $content string */
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

    <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center space-y-4">
        <!-- Upvote Button -->
        <button id="upvote" class="w-14 h-14 flex items-center justify-center rounded-full border-2 border-gray-400 text-gray-600 hover:bg-green-500 hover:text-white transition duration-300 text-2xl">
            ▲
        </button>

        <!-- Vote Count -->
        <p id="vote-count" class="text-4xl font-bold text-gray-800 transition duration-300">0</p>

        <!-- Downvote Button -->
        <button id="downvote" class="w-14 h-14 flex items-center justify-center rounded-full border-2 border-gray-400 text-gray-600 hover:bg-red-500 hover:text-white transition duration-300 text-2xl">
            ▼
        </button>
    </div>

    </div>

    <div class="flex-1">
        <?= $this->render('_view_category_index', ['page' => $page]) ?>

        <?php if (!empty($content)): ?>
            <div class=" min-h-[212px] markdown-render bg-white rounded-lg p-4  transition-all duration-300 ease-in-out hover:shadow-xl border-l-4 border-gray-200 text-lg leading-relaxed"
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
                        ->link(Url::toWikiEdit($page))
                        ->icon('fa-pencil-square-o')
                        ->addClass('bg-gradient-to-r from-green-400 to-green-600 hover:from-green-500 hover:to-green-700 text-white font-bold py-4 px-8 rounded-lg shadow-lg transition-all duration-300 ease-in-out text-xl')
                        ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>