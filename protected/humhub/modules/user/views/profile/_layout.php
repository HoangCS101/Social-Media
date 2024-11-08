<?php

use humhub\modules\user\widgets\ProfileHeader;
use humhub\modules\user\widgets\ProfileMenu;
use humhub\widgets\FooterMenu;

use humhub\modules\content\widgets\richtext\RichText;
use humhub\modules\ui\view\components\View;
use yii\helpers\Html;
use humhub\modules\user\models\fieldtype\MarkdownEditor;

$user = $this->context->contentContainer;
$categories = $user->profile->getProfileFieldCategories();
?>
<div class="container profile-layout-container">
    <div class="row">
        <div class="col-md-12">
            <?= ProfileHeader::widget(['user' => $user]); ?>
        </div>
    </div>
    <div class="row profile-content">
        <div class="col-md-3 layout-nav-container">
            <?= ProfileMenu::widget(['user' => $user]); ?>
            <div class="ui-block">
				<div class="ui-block-title">
					<h6 class="title">Profile Intro</h6>
				</div>
				<div class="panel-body">
                    <!-- <?php $firstClass = "active" ?>
                    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                        <?php foreach ($categories as $category): ?>
                            <li class="<?= $firstClass ?>">
                                <a href="#profile-category-<?= $category->id; ?>"
                                data-toggle="tab"><?= Html::encode(Yii::t($category->getTranslationCategory(), $category->title)) ?></a>
                            </li>
                            <?php
                            $firstClass = "";
                        endforeach;
                        ?>
                    </ul>
                    <?php $firstClass = "active" ?> -->
                    <div class="tab-content">
                        <?php foreach ($categories as $category): ?>
                            <div class="tab-pane <?php
                            echo $firstClass;
                            $firstClass = "";
                            ?>" id="profile-category-<?= $category->id ?>">
                                <form class="form-horizontal" role="form">
                                    <?php foreach ($user->profile->getProfileFields($category) as $field) : ?>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">
                                                <?= Html::encode(Yii::t($field->getTranslationCategory(), $field->title)) ?>
                                            </label>
                                            <?php if (strtolower($field->title) == 'about'): ?>
                                                <div class="col-sm-7">
                                                    <div
                                                        class="form-control-static"><?= RichText::output($field->getUserValue($user, true)) ?></div>
                                                </div>
                                            <?php else: ?>
                                                <div class="col-sm-7">
                                                    <?php if ($field->field_type_class == MarkdownEditor::class): ?>
                                                        <p class="form-control-static"
                                                        style="min-height: 0 !important;padding-top:0;">
                                                            <?= RichText::output($field->getUserValue($user, true)) ?>
                                                        </p>
                                                    <?php else: ?>
                                                        <p class="form-control-static"><?= $field->getUserValue($user, false) ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
			</div>
        </div>
        <div class="col-md-<?= ($this->hasSidebar()) ? '6' : '9' ?> layouts-content-container">
            <?= $content; ?>
            <?php if (!$this->hasSidebar()): ?>
                <?= FooterMenu::widget(['location' => FooterMenu::LOCATION_FULL_PAGE]); ?>
            <?php endif; ?>
        </div>
        <?php if ($this->hasSidebar()): ?>
            <div class="col-md-3 layout-sidebar-container">
                <?= $this->getSidebar() ?>
                <?= FooterMenu::widget(['location' => FooterMenu::LOCATION_SIDEBAR]); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
