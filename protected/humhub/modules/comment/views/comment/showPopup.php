<?php

use humhub\libs\Html;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\ui\view\components\View;
use humhub\widgets\ModalDialog;
use humhub\modules\comment\widgets\Form;

/* @var $this View */
/* @var $object ContentActiveRecord */

?>

<?php ModalDialog::begin(['header' => Yii::t('CommentModule.base', 'Comments')]) ?>
<div class="modal-body comment-container comment-modal-body" style="margin-top:0">
    <div id="userlist-content">
        <div class="well well-small" id="comment_<?= $id ?>">
            <div class="comment w-full" id="comments_area_<?= $id ?>">
                <?= $output ?>
            </div>
            <?= Form::widget(['object' => $object]); ?>
        </div>
    </div>
</div>
<?php ModalDialog::end() ?>

<script <?= Html::nonce() ?>>
    // scroll to top of list
    $(".comment-modal-body").animate({scrollTop: 0}, 200);

    <?php if(empty(trim($output))) : ?>
    $('#comment_<?= $id ?>').find('.comment_create hr').hide();
    <?php endif; ?>
</script>


