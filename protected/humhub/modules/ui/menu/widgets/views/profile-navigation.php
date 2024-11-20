<?php

use humhub\libs\Html;
use humhub\modules\ui\menu\MenuEntry;
use humhub\modules\ui\menu\widgets\LeftNavigation;
use humhub\modules\ui\view\components\View;

/* @var $this View */
/* @var $menu LeftNavigation */
/* @var $entries MenuEntry[] */
/* @var $options [] */
?>

<?= Html::beginTag('div', $options) ?>
<ul class="profile-menu">
    <?php foreach ($entries as $entry): ?>
        <li>
            <?= $entry->render(['class' => 'a']) ?>
        </li>
    <?php endforeach; ?>
    </ul>
<?= Html::endTag('div') ?>