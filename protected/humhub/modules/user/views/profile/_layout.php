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
            <?= ProfileHeader::widget(['user' => $user]) ?>
        </div>
    </div>
    <div class="row profile-content">
        <div class="col-md-3 layout-nav-container">
            <div class="ui-block">
				<div class="ui-block-title">
					<h6 class="title">Profile Intro</h6>
				</div>
				<div class="ui-block-content">
                    <div class="tab-content">
                        <?php foreach ($categories as $category): ?>
							<ul class="widget w-personal-info">
								<?php foreach ($user->profile->getProfileFields($category) as $field) : ?>
										<li class="pt-[5px] pb-[5px]">
											<?= Html::tag('span', Html::encode(Yii::t($field->getTranslationCategory(), $field->title)), ['class' => 'title']) ?>
											<?php if (strtolower($field->title) == 'about'): ?>
													<span class="text"><?= RichText::output($field->getUserValue($user, true)) ?></span>
											<?php else: ?>
												
													<?php if ($field->field_type_class == MarkdownEditor::class): ?>
														<span
														class="text">
															<?= RichText::output($field->getUserValue($user, true)) ?>
													</span>
													<?php else: ?>
														<span class="text"><?= $field->getUserValue($user, false) ?></span>
													<?php endif; ?>
											<?php endif; ?>
										</li>
										
								<?php endforeach; ?>
							</ul>
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
