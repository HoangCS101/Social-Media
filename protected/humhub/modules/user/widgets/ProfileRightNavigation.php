<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2016 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\user\widgets;

use humhub\modules\user\Module;
use Yii;
use humhub\modules\ui\menu\MenuLink;
use humhub\modules\ui\menu\widgets\ProfileHeaderNavigation;
use humhub\modules\user\models\User;
use humhub\modules\user\permissions\ViewAboutPage;

/**
 * ProfileMenuWidget shows the (usually left) navigation on user profiles.
 *
 * Only a controller which uses the 'application.modules_core.user.ProfileControllerBehavior'
 * can use this widget.
 *
 * The current user can be gathered via:
 *  $user = Yii::$app->getController()->getUser();
 *
 * @since 0.5
 * @author Luke
 */
class ProfileRightNavigation extends ProfileHeaderNavigation
{
    /**
     * @var User
     */
    public $user;


    /**
     * @inheritdoc
     */
    public function init()
    {

        $this->panelTitle = Yii::t('UserModule.profile','');

        /** @var Module $module */
        $module = Yii::$app->getModule('user');

        // if (!$module->profileDisableStream) {
            
        // }
        $this->addEntry(new MenuLink([
            'label' => Yii::t('UserModule.profile', 'Photos'),
            // 'icon' => 'stream',
            'url' => $this->user->createUrl('//user/profile/home'),
            'sortOrder' => 200,
            'isActive' => MenuLink::isActiveState('user', 'profile', 'photos'),
        ]));

        $this->addEntry(new MenuLink([
            'label' => Yii::t('UserModule.profile', 'Video'),
            // 'icon' => 'about',
            'url' => $this->user->createUrl('/user/profile/about'),
            'sortOrder' => 300,
            'isActive' => MenuLink::isActiveState('user', 'profile', 'videos'),
            // 'isVisible' => $this->user->permissionManager->can(ViewAboutPage::class),
        ]));

        $this->addEntry(new MenuLink([
            'label' => Yii::t('UserModule.profile', 'Settings'),
            // 'icon' => 'about',
            'url' => $this->user->createUrl('/user/account/edit'),
            'sortOrder' => 300,
            'isActive' => MenuLink::isActiveState('user', 'account', 'edit'),
            // 'isVisible' => $this->user->permissionManager->can(ViewAboutPage::class),
        ]));

        

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (Yii::$app->user->isGuest && $this->user->visibility != User::VISIBILITY_ALL) {
            return '';
        }

        return parent::run();
    }

}
