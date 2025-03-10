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

<div class="flex items-center space-x-3 mt-6 p-4 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-lg shadow-lg text-[16px]">
    <h1 class="text-4xl font-extrabold">Topic:</h1>
    <h1 class="text-4xl font-extrabold"> <?= Html::encode($page->title) ?> </h1>
</div>
<br>
<?= $this->render('_view_category_index', ['page' => $page]) ?>

<?php if (!empty($content)): ?>
    <div 
        class=" markdown-render bg-white shadow-lg rounded-lg p-8 mt-6 transition-all duration-300 ease-in-out hover:shadow-xl border-l-4 border-blue-500 text-lg leading-relaxed" 
        data-ui-widget="wiki.Page" 
        <?= $canEdit ? ' data-edit-url="' . Url::toWikiEdit($page) . '"' : '' ?> 
        data-ui-init 
        style="display:none"
    >
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

