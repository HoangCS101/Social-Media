<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\admin\permissions\ManageModules;
use humhub\modules\admin\permissions\ManageSettings;
use humhub\modules\admin\widgets\AvailableModuleUpdatesInfo;
use humhub\modules\admin\widgets\InstalledModuleList;
use humhub\modules\marketplace\widgets\MarketplaceLink;

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('AdminModule.base', '<strong>Module</strong> administration') ?>
        <?= MarketplaceLink::info(Yii::t('AdminModule.base', 'Add more modules'))
            ->icon('external-link')
            ->right()->sm() ?>
    </div>
    <div class="panel-body">
        <div class="help-block">
            <?= Yii::t('AdminModule.base', 'This overview shows you all installed modules and allows you to enable, disable, configure and of course uninstall them. To discover new modules, take a look into our Marketplace. Please note that deactivating or uninstalling a module will result in the loss of any content that was created with that module.') ?>
        </div>

        <?php if (!Yii::$app->user->can(ManageModules::class)) : ?>
            <div class="alert alert-info">
                <?= Yii::t('AdminModule.base', 'You do not have the permission to manage modules. Please contact the administrator for further information.') ?>
            </div>
        <?php endif; ?>

        <?php if (!Yii::$app->user->can(ManageSettings::class)) : ?>
            <div class="alert alert-info">
                <?= Yii::t('AdminModule.base', 'You do not have the permission to configure modules. Please contact the administrator for further information.') ?>
            </div>
        <?php endif; ?>

        <?= AvailableModuleUpdatesInfo::widget() ?>
        <?= InstalledModuleList::widget() ?>

        <div class="flex gap-3">
            <?= MarketplaceLink::primary(Yii::t('AdminModule.base', 'Visit Marketplace'))
                ->icon('external-link') ?>
            <button id="enroll-admin-btn" class="btn btn-success">
                <i class="fa fa-user-plus"></i> <?= Yii::t('AdminModule.base', 'Enroll Fabric Admin') ?>
            </button>
        </div>
        
    </div>
</div>

<?php
$enrollUrl = \yii\helpers\Url::to(['/admin/secure/enroll-admin']);
$csrfToken = Yii::$app->request->getCsrfToken();
$js = <<<JS
    $('#enroll-admin-btn').on('click', function() {

        $.ajax({
            url: $enrollUrl,
            type: 'POST',
            // data: {
            //     _csrf: $csrfToken
            // },
            success: function(response) {
                alert('Successfully enrolled as admin!');
                location.reload(); 
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'Unknown error'));
            }
        });
    });
JS;

$this->registerJs($js);
