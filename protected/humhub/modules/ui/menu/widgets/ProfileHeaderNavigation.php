<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\ui\menu\widgets;

/**
 * Class LeftNavigation
 *
 * @since 1.4
 * @package humhub\modules\ui\menu\widgets
 */
abstract class ProfileHeaderNavigation extends Menu
{
    /**
     * @var string the title of the panel
     */
    public $panelTitle;

    /**
     * @inheritdoc
     */
    public $template = '@ui/menu/widgets/views/profile-navigation.php';

    /**
     * @inheritdoc
     */
    public function getAttributes()
    {
        return [
            'class' => 'div',
        ];
    }


}
