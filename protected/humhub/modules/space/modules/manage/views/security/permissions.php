<?php

use humhub\modules\user\widgets\PermisionGridModuleFilter;
use humhub\modules\user\widgets\PermissionGridEditor;
use humhub\modules\space\modules\manage\widgets\SecurityTabMenu;

?>

<div class="panel panel-default">
    <div>
        <div class="panel-heading">
            <?= Yii::t('SpaceModule.manage', '<strong>Security</strong> settings'); ?>
        </div>
    </div>

    <?= SecurityTabMenu::widget(['space' => $space]); ?>

    <div class="panel-body">
        <p class="help-block"><?= Yii::t('SpaceModule.manage', 'Permissions are assigned to different user-roles. To edit a permission, select the user-role you want to edit and change the drop-down value of the given permission.'); ?></p>
    </div>

    <div class="pull-right" style="margin-right:20px">
        <?= PermisionGridModuleFilter::widget() ?>
    </div>
    <div class="clearfix"></div>

    <ul id="tabs" class="nav nav-tabs tab-sub-menu permission-group-tabs">
        <?php foreach ($groups as $currentGroupId => $groupLabel) : ?>
            <li class="<?= ($groupId === $currentGroupId) ? 'active' : '' ?>">
                <a href='<?= $space->createUrl('permissions', ['groupId' => $currentGroupId]); ?>'><?= $groupLabel ?></a>
            </li>
        <?php endforeach; ?>
    </ul>


    <div class="panel-body" style="padding-top: 0px;">
        <?= PermissionGridEditor::widget(['permissionManager' => $space->permissionManager, 'groupId' => $groupId]); ?>
    </div>
</div>
