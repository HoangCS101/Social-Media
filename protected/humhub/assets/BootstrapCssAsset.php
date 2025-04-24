<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\assets;

use humhub\components\assets\AssetBundle;

/**
 * animate.css
 *
 * @author buddha
 */
class BootstrapCssAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $defaultDepends = false;

    /**
     * @inheritdoc
     */
    public $sourcePath = '@humhub/assets/template/Bootstrap/dist';

    /**
     * @inheritdoc
     */
    public $css = ['css/bootstrap-grid.css', 'css/bootstrap-reboot.css', 'css/bootstrap.css'];
    // public $js = ['js/bootstrap.bundle.js'];
}
