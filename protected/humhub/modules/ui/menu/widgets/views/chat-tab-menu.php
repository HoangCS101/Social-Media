<?php

use humhub\libs\Html;
use humhub\modules\ui\menu\MenuEntry;
use humhub\modules\ui\menu\widgets\DropdownMenu;
use humhub\modules\ui\view\components\View;

/* @var $this View */
/* @var $menu DropdownMenu */
/* @var $entries MenuEntry[] */
/* @var $options [] */
?>

<?= Html::beginTag('div', $options) ?>
<ul class="nav nav-tabs mb-0">
    <?php foreach ($entries as $entry): ?>
        <?php 
            $classes = 'w-50 text-center';
            if ($entry->getIsActive()) {
                $classes .= ' active';
            }
        ?>
        <li class="<?= $classes ?>">
            <?= $entry->render() ?>
        </li>
    <?php endforeach; ?>
</ul>
<?= Html::endTag('div') ?>
