<?php

use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\widgets\stream\WallStreamEntryOptions;
use humhub\modules\content\widgets\WallEntryAddons;
use humhub\modules\ui\view\components\View;

/* @var $this View */
/* @var $model ContentActiveRecord */
/* @var $renderOptions WallStreamEntryOptions */

?>

<?php if (!$renderOptions->isAddonsDisabled()) : ?>
    <a href="#" class="post-add-icon inline-items">
        <i class="fa fa-heart"></i>
        <span>49</span>
    </a>
    <ul class="friends-harmonic">
        <li>
            <a href="#">
                <img src="img/friend-harmonic9.jpg" alt="friend">
            </a>
        </li>
        <li>
            <a href="#">
                <img src="img/friend-harmonic10.jpg" alt="friend">
            </a>
        </li>
        <li>
            <a href="#">
                <img src="img/friend-harmonic7.jpg" alt="friend">
            </a>
        </li>
        <li>
            <a href="#">
                <img src="img/friend-harmonic8.jpg" alt="friend">
            </a>
        </li>
        <li>
            <a href="#">
                <img src="img/friend-harmonic11.jpg" alt="friend">
            </a>
        </li>
    </ul>
    <div class="stream-entry-addons clearfix">
        <?= WallEntryAddons::widget(['object' => $model, 'renderOptions' => $renderOptions]) ?>
    </div>
<?php endif; ?>
