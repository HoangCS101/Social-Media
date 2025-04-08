<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

 namespace humhub\modules\mail\widgets;

use Yii;
use yii\helpers\Html;
use humhub\modules\ui\menu\MenuLink;
use humhub\modules\ui\menu\widgets\TabMenu;
// use humhub\modules\admin\permissions\ManageSettings;

class SecureChatMenu extends TabMenu
{
    public $template = '@ui/menu/widgets/views/sub-tab-menu.php';
    /**
     * @inheritdoc
     */
    public $filter;

    public function init()
    {
        // $canEditSettings = Yii::$app->user->can(ManageSettings::class);
        $currentType = Yii::$app->request->get('type', 'normal');

        $this->addEntry(new MenuLink([
            'label' => 'Normal Chat',
            'url' => ['/mail/mail/index', 'type' => 'normal'],
            'sortOrder' => 100,
            'isActive' => ($currentType === 'normal'),
            'isVisible' => true,
            'htmlOptions' => ['class' => 'line']
        ]));

        $this->addEntry(new MenuLink([
            'label' => 'Secure Chat',
            'url' => ['/mail/mail/index', 'type' => 'secure'],
            'sortOrder' => 200,
            'isActive' => ($currentType === 'secure'),
            'isVisible' => true,
            // 'htmlOptions' => ['class' => 'w-50']
        ]));

        $this->template = '@ui/menu/widgets/views/chat-tab-menu.php';
        parent::init();
    }

}
