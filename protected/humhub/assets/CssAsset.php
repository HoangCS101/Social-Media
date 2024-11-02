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
class CssAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $defaultDepends = false;

    /**
     * @inheritdoc
     */
    public $sourcePath = '@humhub/assets/template/css';

    /**
     * @inheritdoc
     */
    public $css = ['fonts.min.css', 'main.min.css'];
    // public $js = ['js/bootstrap.bundle.js'];
}
