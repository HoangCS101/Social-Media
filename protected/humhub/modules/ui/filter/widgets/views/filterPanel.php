<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

use humhub\modules\ui\filter\widgets\FilterBlock;
use humhub\modules\ui\view\components\View;

/* @var $this View */
/* @var $span int */
/* @var $blocks [] */

$colSpan = $span <= 4 ? 12 / $span : 6;

?>

<div class="filter-panel col-md-<?= $colSpan ?>">
    <?php foreach ($blocks as $block): ?>
        <?= FilterBlock::widget($block) ?>
    <?php endforeach; ?>
</div>
