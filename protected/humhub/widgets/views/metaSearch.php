<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\assets\SearchAsset;
use humhub\interfaces\MetaSearchProviderInterface;
use humhub\libs\Html;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\widgets\Button;
use humhub\widgets\Link;
use humhub\widgets\MetaSearchProviderWidget;
use yii\web\View;

/* @var View $this */
/* @var array $options */
/* @var MetaSearchProviderInterface[] $providers */

SearchAsset::register($this);

$isAllHiddenEmpty = empty($providers) || array_reduce($providers, function ($carry, $provider) {
    return $carry && $provider->getIsHiddenWhenEmpty();
}, true);


?>
<?= Html::beginTag('li', $options) ?>
    <?= Link::asLink('')
        ->icon('search')
        ->id('search-menu')
        ->action('menu')
        ->options(['data-toggle' => 'dropdown'])
        ->cssClass('dropdown-toggle') ?>
    
    <div id="dropdown-search" class="dropdown-menu " 
        style="display: flex; 
            top: 17%; left: 0; 
            right: auto; 
            width: 400px;
            flex-direction: column">
        <div class="dropdown-header" style="display: none">
            <div class="arrow"></div>
            <?= Yii::t('base', 'Search') ?>
            <?= Icon::get('close', ['id' => 'dropdown-search-close']) ?>
        </div>
        <div class="dropdown-search-form p-0 w-[500px] z-[9999]">
            <?= Button::defaultType()
                ->icon('search')
                ->action('search')
                ->cssClass('dropdown-search-button')
                ->loader(false) ?>
            <?= Html::input('text', 'keyword', '', [
                'class' => 'dropdown-search-keyword form-control',
                'autocomplete' => 'off',
                'placeholder' => 'Search here ...'
            ]) ?>
        </div>
        <ul class="dropdown-search-list z-[10]" style="display: <?= $isAllHiddenEmpty ? 'none !important' : 'block' ?>;">
            <?php foreach ($providers as $provider) : ?>
                <?= MetaSearchProviderWidget::widget(['provider' => $provider]) ?>
            <?php endforeach; ?>
        </ul>
    </div>


<?= Html::endTag('li') ?>
<script>

    document.addEventListener('DOMContentLoaded', function () {
        const dropdownSearch = document.getElementById('#dropdown-search');
        const dropdownSearchList = document.querySelector('.dropdown-search-list');

        
    });

</script>
