<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\assets;

use humhub\components\assets\AssetBundle;

/**
 * jquery-ui-widget
 *
 * @author luke
 */
class JqueryWidgetAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/jquery-ui';

    /**
     * @inheritdoc
     */
    public $js = ['ui/minified/widget.js'];

}
