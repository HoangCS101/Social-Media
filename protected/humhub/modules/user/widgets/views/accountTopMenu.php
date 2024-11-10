<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\ui\menu\DropdownDivider;
use humhub\modules\ui\menu\MenuEntry;
use humhub\modules\ui\menu\widgets\DropdownMenu;
use humhub\modules\ui\view\components\View;
use humhub\modules\user\widgets\Image;
use humhub\widgets\FooterMenu;
use yii\helpers\Html;

/* @var $this View */
/* @var $menu DropdownMenu */
/* @var $entries MenuEntry[] */
/* @var $options [] */

/** @var \humhub\modules\user\models\User $userModel */

$userModel = Yii::$app->user->identity;

?>

<?php if (Yii::$app->user->isGuest): ?>
    <?php if (!empty($entries)): ?>
        <?= $entries[0]->render() ?>
    <?php endif; ?>
<?php else: ?>
    <?= Html::beginTag('ul', $options) ?>
    <li class="dropdown account pt-[10px] hover:none">
        <div href="#" id="account-dropdown-link" class="py-0 my-auto hover:cursor-pointer" data-toggle="dropdown"
            aria-label="<?= Yii::t('base', 'Profile dropdown') ?>">
            <?= Image::widget([
                'user' => $userModel,
                'link' => false,
                'width' => 40,
                'htmlOptions' => ['id' => 'user-account-image'],
                'showSelfOnlineStatus' => true,
            ]) ?>
            <?php if ($this->context->showUserName): ?>
                <div class="user-title pull-right hidden-xs text-white pl-2">
                    <strong><?= Html::encode($userModel->displayName); ?></strong><br /><span
                        class="truncate"><?= Html::encode($userModel->displayNameSub); ?></span>
                </div>
            <?php endif; ?>

        </div>
        <ul class="dropdown-menu pull-right w-[300px] mt-2 bg-[#515365] ">
            <li class="ui-block-title ui-block-title-small">
                <h6 class="title">YOUR ACCOUNT</h6>
            </li>
            <?php foreach ($entries as $entry): ?>
                <?php if (!($entry instanceof DropdownDivider)): ?>
                    <li class="text-black"><?php endif; ?>
                    <?= $entry->render() ?>
                    <?php if (!($entry instanceof DropdownDivider)): ?>
                    </li><?php endif; ?>
            <?php endforeach; ?>
            <?= FooterMenu::widget(['location' => FooterMenu::LOCATION_ACCOUNT_MENU]); ?>
        </ul>
    </li>
    <?= Html::endTag('ul') ?>
<?php endif; ?>