<?php
use humhub\modules\mail\widgets\ConversationInbox;
?>

<div id="inboxTab" class="tab-content">
    <?php if ($type === 'normal') { ?>
        <div class="inbox-wrapper">
            <hr style="margin:0">
            <?= ConversationInbox::widget(['filter' => $filter]) ?>
        </div>
    <?php } else { ?>
        <div class="inbox-wrapper">
            <hr style="margin:0">
            <?= ConversationInbox::widget(['type'=> $type, 'filter' => $filter]) ?>
        </div>
    <?php } ?>

</div>