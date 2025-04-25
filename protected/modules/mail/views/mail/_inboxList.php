<?php
use humhub\modules\mail\widgets\ConversationInbox;
?>

<div id="inboxTab" class="tab-content">
    <div class="inbox-wrapper">
        <hr style="margin:0">
        <?= ConversationInbox::widget(['type'=> $type, 'filter' => $filter]) ?>
    </div>
</div>